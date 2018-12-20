#!/usr/bin/perl
use strict;
use threads;
use threads::shared;
use Thread::Queue;
use warnings;
use Data::Dumper; #To display data.
use Date::Parse; #Converting date/time format to unix timestamp
use DBI;
use Expect;
use WWW::Mechanize;
use Try::Tiny;
my $sanity_tb1 = new Expect();
my $host="sjc-dbdl-mysql4";
my $database = $ARGV[0];
#print "Current database is $database\n";
#my $port = 3700;
my $userid = "irs";
my $password = "irs";
my $dbh = DBI->connect("DBI:mysql:database=$database;host=$host;",$userid, $password)  or die "Could not connect to database: $DBI::errstr";
my $startDate = $ARGV[1];
my $endDate = $ARGV[2];
my $branch = $ARGV[3];
my $username = $ARGV[4];
my $CFD = $ARGV[5];
my $UploadCFD = $ARGV[6];
my $platform="c2960x";
no warnings 'uninitialized';
my @CFD_Array = map defined( $_ ) ? $_ : '', split /,/,$ARGV[5];
print @CFD_Array;
my @UploadCFD_Array = split /,/,$ARGV[6];
foreach (@CFD_Array) {
    unless (/CSC[a-zA-Z]{2}\d{5}/) {
         return "Invalid CFD $_";
    }
}
   $sanity_tb1->spawn('ssh satkommu@scapa-ind01-lnx') or die "Cannot spawn sftp command \n";
#   $sanity_tb1->expect(100, ["Enter passphrase for key '/users/maqsahme/.ssh/id_rsa':"]);
#   $sanity_tb1->send("\r\n");
   $sanity_tb1->expect(100, ["password:"]);
   $sanity_tb1->send("Kommu123\$\n");
   $sanity_tb1->expect(200,'-re','[#>$]') or die "Din't get the prompt";
my $mech = WWW::Mechanize->new();
$mech->cookie_jar(HTTP::Cookies->new());
my $sth = $dbh->prepare("DELETE FROM image WHERE username='$username'") || die "$DBI::errstr";
#$sth->execute();
#print "DELETE FROM Mapping WHERE username='$username'";
#print "DELETE FROM image WHERE username='$username'";
#$sth = $dbh->prepare('CREATE TABLE Mapping(Bug_Id varchar(25) NOT NULL PRIMARY KEY, Dev_Enginner varchar(50), Headings varchar(255))') || die "$DBI::errstr";
#$sth->execute();
#print "$startDate.............$endDate\n";
my $secConvStartDate = str2time($startDate);
my $secConvEndDate = str2time($endDate);
## Create the time stamp with date
my ($Start_Date_Time,$End_Date_Time,@bugArray);
my (@chStringArray,$chString);
my (@fnStringArray,$fnString);
my (@compStringArray,$compString): shared;
my (@IndexStringArray,$IndexString);
my (@bugstringArray,$bugstring) : shared;
my $cs : shared;
my $iter=0;
my %h :shared;
my %g :shared;
my ($key,$val,$chFile,$fn,$index) : shared;
sub addValue
{
    my ($key,$val) = @_;
    if(!$h{$key})
    {
        $h{$key} = $val;
    }
    else
    {
        $h{$key} .= ",";
        $h{$key} .= $val;
    }
}

sub addbugid_component 
{	
	
    my ($key,$val) = @_;
    if(!$g{$key})
    {
        $g{$key} = $val;
    }
    else
    {
        $g{$key} .= ",";
        $g{$key} .= $val;
    }
}

push (@bugArray,@CFD_Array,@UploadCFD_Array);

if ($startDate ne 'NULL') {
	if ($startDate =~ /(\d+)\/(\d+)\/(\d+)/) {
	    my $month = $1;
	    my $date = $2;
	    my $year = $3;
	    my %month_hash = (1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',
                      8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
	    foreach my $i (keys %month_hash) {
	        if ($i == $month) {
	            $month = $month_hash{$i};
	            last;
	        }
	    }
	    $Start_Date_Time = 'time:'."$date-".$month."-$year".'.00:00:00';
	} else {
	    return 0;
	}
	if ($endDate =~ /(\d+)\/(\d+)\/(\d+)/) {
	    my $month = $1;
	    my $date = $2;
	    my $year = $3;
	    my %month_hash = (1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',
                      8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
	    foreach my $i (keys %month_hash) {
	        if ($i == $month) {
	            $month = $month_hash{$i};
	            last;
	        }
	    }
	    $End_Date_Time = 'time:'."$date-".$month."-$year".'.00:00:00';
	} else {
	    return 0;
	}
#print "$Start_Date_Time      $End_Date_Time\n";
##CC_List command
#my @CC_List = `cc_list_bugs -vob /vob/ios -branch dsgs_pi5 -from "$Start_Date_Time" -to "$End_Date_Time"`;
if ($database eq 'irs') {
    #print "Searching in $database\n";
    #determine the number of days from start date to end date.
    my $days = abs ($secConvEndDate - $secConvStartDate) / 86400;
    #print "Total number of days $days\n";
    #my $buildpath = '/ws/gsbu-build33/CBAS/archive/beni_e2/prod/wabRelease/bundles';
    #my @output = `ls -ltr /ws/gsbu-build33/CBAS/archive/beni_e2/prod/wabRelease/bundles`;
    my $buildpath = '/auto/smuarchive1/6.1.3';
    my @output = `ls -ltr /auto/smuarchive1/6.1.3`;
    #print "\@output @output";
    for (my $i = 0; $i <= $days; $i++) {
        my $dateFormatStart = localtime($secConvStartDate);
        my @mixedDates = split(" ", $dateFormatStart);
        my $month = $mixedDates[1];
        my $date = $mixedDates[2];
        #print "\$month\\\$date $month\\$date\n";
        foreach my $path(@output) {
            if ($path =~ "$month\\s+$date\\s+\\d+:\\d+\\s+(\\S+)") {
                my @bugOutPut = `cat $buildpath/$1/deliverables/$1.txt`;
                foreach my $out (@bugOutPut) {
                   if ($out =~ /^DDTS:\s+(CSC[a-zA-Z]{2}\d{5})/) {
                       #print "matched $out\n";
                       $out=$1;
                       #print "new match $out\n";
                       push (@bugArray,$out);
                       #my @array = split(" ", $out, 3);
                       #$sth = $dbh->prepare("INSERT INTO Mapping (Bug_Id, Dev_Enginner, Headings, username) VALUES ('$array[0]','$array[1]', '$array[2]','$username')");
                       #$sth->execute();
                   }
                }
    #        } else {
    #            print "image build is not available for $dateFormatStart\n\n";
            }
        }
        $secConvStartDate = $secConvStartDate + 86400;
    }
}
#print "\@bugArray is @bugArray\n";
   unless (@bugArray) {
     #return 0;
   }
}
our $THREADS = 300;
my $Qwork = Thread::Queue->new();
my $retq = Thread::Queue->new();
## Create the pool of workers
my @pool = map{
    threads->create( \&product_details, $Qwork,$retq)
} 1 .. $THREADS;

$Qwork->enqueue(@bugArray);
$Qwork->enqueue((undef) x $THREADS);
$_->join for @pool;

for ( 1 .. $THREADS ) {
    while( my $result = $retq->dequeue_timed(5)) {
        ## Do something with the result ##
        print $result;
    }
}

sub product_details {
    #my $tid = threads->tid;
    my $Qwork = shift;

	while(my $bugId = $Qwork->dequeue) {
		#print $bugId;
		my ($Engineer, $Component, $Product, $Headline, @arraySt, $str_array);
		$sanity_tb1->clear_accum();
		#print "****$bugId*****\n";
		$sanity_tb1->send("/usr/cisco/bin/qbugval.pl -i $bugId Engineer Component Product Headline\n");
		try {
		$sanity_tb1->expect(100,'-re','[#>$]') or die "Din't get the prompt";
		}catch {
			sleep(3);
		};
		my $out = $sanity_tb1->exp_before();
		my @array = split /\n/,$out;
		my $g = 1;
		foreach (@array) {
           #print "$g ---> $_\n";
				$g++;
		}
		foreach (1..$#array) {
			if ($array[$_] =~ /(\S+)\s+(\S+)\s+(\S+)\s+(\S+.*)/) {
                #  print "inside if loop\n";
                $Engineer = $1;
                $Component = $2;
                $Product  =$3;
                $Headline = $4;
                #print "\n\n\n\n--------------\n$Headline\n\n\n\n--------------\n";
                if ($Headline =~ s/"/'/g){}
                $sth = "INSERT INTO Mapping(Bug_Id, Dev_Enginner, Headings, Product, Component, username) VALUES('$bugId','$Engineer', \"$Headline\",\"$Product\",\"$Component\",'$username') ON DUPLICATE KEY UPDATE Dev_Enginner='$Engineer',Headings=\"$Headline\",Product=\"$Product\",Component=\"$Component\",username='$username'";
				#print $sth;
				#$dbh->do($sth, { HandleError => \&handle_error });
				try {
					$dbh->do($sth);
					$sth->finish;
				}catch {
					#die $_ unless /execute failed: Duplicate entry/
					print "Duplicate Entry";
				};
           }
		}
=pod
	my $datestring = localtime();
	#print "Local date and time  before prrqlink $datestring\n";
	$bugstring=$bugId;
	#print "****$bugId*****\n";
	push (@bugstringArray,$bugstring)if (!grep /$bugstring/, @bugstringArray);
	my $url="http://prrq.cloudapps.cisco.com/prrq/viewReview.do?action=show_diff&bugId=$bugId";
	my $mech = WWW::Mechanize->new();
	$mech->cookie_jar(HTTP::Cookies->new());
	$mech->get($url);
	$mech->form_id("login-form");
	$mech->field("userid", "satkommu");
	$mech->field("password", "Kommu123\$");
	my $tr=$mech->click;
	if ($tr->is_success)
	{
	  my $stat=1;
	}
  else {
		sleep(10);
      print STDERR $tr->status_line, "\n";
     }
	#print("staus====$tr====");
	my $status = 0;
	my @output_prrq = $mech->content();
	#print @output_prrq;
	 my $filename = 'report.txt';
	open(my $fh, '>', $filename) or die "Could not open file '$filename' $!";
	print $fh "@output_prrq\n";
	close $fh;
	print "done\n";
	$datestring = localtime();
	#print "Local date and time after prrq $datestring\n";
	if ((grep /"Navigation Error"/, @output_prrq) || ((grep/"Welcome,"/, @output_prrq) && (grep/"Log Out"/, @output_prrq)))
	{
		#print "i am logged in\n";
		$status = 1;
	}
	#print @output_prrq;
	foreach my $line_prrq (@output_prrq)
	{
		my @lineArray_prrq = split /\n/,$line_prrq;
		foreach my $eachLine_prrq (@lineArray_prrq)
		{
			if ($eachLine_prrq =~ /Component:\s(\S+)@/)
			{
				#print $eachLine_prrq;
				$compString=$1;
				push (@compStringArray, $compString) if (!grep /$compString/, @compStringArray);
				#print $eachBugIds;
				addbugid_component($bugId,$compString);
				#print "<.......$compString.....>>>";
			}
			if (@bugstringArray)
			{
			   $bugstring=join(",",@bugstringArray);
			}
			if (@chStringArray)
			{
				$chString = join(",", @chStringArray);
			}
			if (@compStringArray)
			{
				$compString = join(",", @compStringArray);
			}
		}
	}
	push (@bugArray,@CFD_Array,@UploadCFD_Array);
    }
=cut
	}
	$retq->enqueue(undef);
	
}


#print "+++++";
#print keys %g;

print "++++";
print "$_," for keys %g;
print "----";
foreach $key (keys %g)
{
   print "$g{$key}+";
}

@chStringArray = ();
@compStringArray = ();
@bugstringArray = ();
$dbh->disconnect;
$sanity_tb1->hard_close();
