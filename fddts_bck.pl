#!/usr/bin/perl
use strict;
use warnings;
use Data::Dumper; #To display data.
use Date::Parse; #Converting date/time format to unix timestamp
use DBI;
use Expect;
use WWW::Mechanize;

   my $sanity_tb1 = new Expect();

my $host="sjc-dbdl-mysql4";
my $database = $ARGV[0];
#print "Current database is $database\n";
#my $port = 3700;
my $userid = "irs";
my $password = "irs";
my $dbh = DBI->connect("DBI:mysql:database=$database;host=$host;",
                       $userid, $password)  or die "Could not connect to database: $DBI::errstr";
my $startDate = $ARGV[1];
my $endDate = $ARGV[2];
my $branch = $ARGV[3];
my $username = $ARGV[4];
my $CFD = $ARGV[5];
my $UploadCFD = $ARGV[6];
my $platform="c2960x";
no warnings 'uninitialized';
my @CFD_Array = map defined( $_ ) ? $_ : '', split /,/,$ARGV[5];
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
my $sth = $dbh->prepare("DELETE FROM Mapping WHERE username='$username'") || die "$DBI::errstr";
$sth->execute();
$sth = $dbh->prepare("DELETE FROM image WHERE username='$username'") || die "$DBI::errstr";
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
my (@compStringArray,$compString);
my (@IndexStringArray,$IndexString);
my %h= ();
my %g= () ;
my ($key,$val,$chFile,$fn,$index);
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
sub addprrqValue
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
my (@arraySt,$arraySt1,$str_array);
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
#    print "Total number of days $days\n";
    #my $buildpath = '/ws/gsbu-build33/CBAS/archive/beni_e2/prod/wabRelease/bundles';
    #my @output = `ls -ltr /ws/gsbu-build33/CBAS/archive/beni_e2/prod/wabRelease/bundles`;
    my $buildpath = '/auto/smuarchive1/6.1.3';
    my @output = `ls -ltr /auto/smuarchive1/6.1.3`;
 #   print "\@output @output";
    for (my $i = 0; $i <= $days; $i++) {
        my $dateFormatStart = localtime($secConvStartDate);
        my @mixedDates = split(" ", $dateFormatStart);
        my $month = $mixedDates[1];
        my $date = $mixedDates[2];
  #      print "\$month\\\$date $month\\$date\n";
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
}
#print "\@bugArray is @bugArray\n";

   unless (@bugArray) {
     #return 0;
   }

foreach my $eachBugIds (@bugArray) {
     my $Enclosures = "/usr/cisco/bin/qbugval.pl -i $eachBugIds ENCLOSURES";
     $sanity_tb1->clear_accum();
     $sanity_tb1->send("$Enclosures\n");
     $sanity_tb1->expect(100, '-re', '[#>$]');
     my $beforeoutput = $sanity_tb1->before();
#     print "\n-----\$beforeoutput-----\n$beforeoutput ---\n";
     my @diffEnc = split(/\s+/, $beforeoutput);
     my @finalEnc = grep(/Diff/, @diffEnc);
#     print "\n\@finalEnc\n@finalEnc----\n";
=pod
     foreach my $enclose (@finalEnc) {
         my $url = "http://cdets.cisco.com/apps/dumpcr_att?identifier=$eachBugIds&title=$enclose&ext=diffs&type=FILE&displaytype=html";
         $mech->get($url);
#         print "\nAfter getting url\n";
         my $response = $mech->response();
#         print "\nAfter respose\n";
         my @content = split /\n/,$response->{_content};
#         print "\nAfter content\n";
         foreach my $line (@content) {
             if ($line =~ /\s+\S+\/(\S+\.c)\@/ || $line =~ /\s+\S+\/(\S+\.h)\@/ || $line =~ /\s+\S+\/(\S+\.cp)\@/ || $line =~ /\s+\S+\/(\S+\.cpp)\@/) {
                 push (@chStringArray, $1) if (!grep /$1/, @chStringArray);
             }
         }
    }
    if (@chStringArray) {
        $chString = join(",", @chStringArray);
#        print $chString;
        # push(@arraySt, $chString);
        #$arraySt1 = join(",", $chString);
        push(@arraySt, $chString);
 }
    @chStringArray = ();
=cut
}
$str_array=join(',',@arraySt);
#print $str_array;
sub uniq {
    my %seen;
    grep !$seen{$_}++, @_;
}
my @filtered = uniq(@bugArray);
foreach my $eachBugIds (@filtered)
{

	my $url="http://prrq.cloudapps.cisco.com/prrq/viewReview.do?action=show_diff&bugId=$eachBugIds";
	my $mech = WWW::Mechanize->new();
	$mech->cookie_jar(HTTP::Cookies->new());
	$mech->get($url);
	$mech->form_id("login-form");
	$mech->field("userid", "satkommu");
	$mech->field("password", "Kommu123\$");
	$mech->click;
	my $status = 0;
	my @output_prrq = $mech->content();
	#print @output_page;
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

			if ($eachLine_prrq =~ /Index:\s+([a-zA-Z0-9_\/]+)[\/](\S+\.c)\</ || $eachLine_prrq =~ /Index:\s+([a-zA-Z0-9_\/]+)[\/](\S+\.h)\</ || $eachLine_prrq =~ /Index:\s+([a-zA-Z0-9_\/]+)[\/](\S+\.cpp)\</|| $eachLine_prrq =~ /Index:\s+([a-zA-Z0-9_\/]+)[\/](\S+\.cp)\</|| $eachLine_prrq =~ /Index:\s+([a-zA-Z0-9_\/]+)[\/](\S+\.cc)\</)
			{
				push (@IndexStringArray, $1) if (!grep /$1/, @IndexStringArray);
				push (@chStringArray, $2) if (!grep /$2/, @chStringArray);
				$chFile=$2;
				$index=$1;
				addprrqValue($compString,$chFile);
				#print "$1--->$2\n";
				#$sth = $dbh->prepare("UPDATE code_coverage SET `Index`='$index' where FileName='$chFile'");
				#$sth->execute();
			}
			
			if ($eachLine_prrq =~ /Component:\s(\S+)@/)
			{
				#print $eachLine_prrq;
				$compString=$1;	
				push (@compStringArray, $compString) if (!grep /$compString/, @compStringArray);	
				#print "<.......$compString.....>>>";
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
}
print $compString;
#print "+++++";
#print keys %g;
print "----";
foreach $key (keys %g)
{
  print "$g{$key}+";
}
@chStringArray = ();
@compStringArray = ();
$dbh->disconnect;
$sanity_tb1->hard_close();


