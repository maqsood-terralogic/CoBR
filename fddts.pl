#!/usr/bin/perl
use strict;
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
my (@compStringArray,$compString);
my (@IndexStringArray,$IndexString);
my (@bugstringArray,$bugstring);
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
=podsub addprrqValue
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
=cut
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
   my ($Engineer, $Component, $Product, $Headline, @arraySt, $str_array);
   foreach my $bugId (@bugArray) {
#print "\n\n\n\n\n bugid $bugId \n\n\n\n";
       $sanity_tb1->clear_accum();
       $sanity_tb1->send("/usr/cisco/bin/qbugval.pl -i $bugId Engineer Component Product Headline\n");
       $sanity_tb1->expect(100,'-re','[#>$]') or die "Din't get the prompt";
       my $out = $sanity_tb1->exp_before();
#print "im here $out\n";
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
   }
push (@bugArray,@CFD_Array,@UploadCFD_Array);

#previous code for chfiles and fn in files
=pod
## Add Enclosures
foreach my $eachBugIds (@bugArray) {
     my $Enclosures = "/usr/cisco/bin/qbugval.pl -i $eachBugIds ENCLOSURES";
     $sanity_tb1->clear_accum();
     $sanity_tb1->send("$Enclosures\n");
     $sanity_tb1->expect(100, '-re', '[#>$]');
     my $beforeoutput = $sanity_tb1->before();
#     print "\n-----\$beforeoutput-----\n$beforeoutput ---\n";
     my @diffEnc = split(/\s+/, $beforeoutput);
     my @finalEnc = grep(/Diff/, @diffEnc);
     #print "\n\@finalEnc\n@finalEnc----\n";
     foreach my $enclose (@finalEnc) {
         my $url = "http://cdets.cisco.com/apps/dumpcr_att?identifier=$eachBugIds&title=$enclose&ext=diffs&type=FILE&displaytype=html";
         $mech->get($url);
#         print "\nAfter getting url\n";
         my $response = $mech->response();
#         print "\nAfter respose\n";
         my @content = split /\n/,$response->{_content};
#         print "\nAfter content\n";
         foreach my $line (@content) {
             if ($line =~ /\s+\S+\/(\S+\.c)\@/ || $line =~ /\s+\S+\/(\S+\.h)\@/ || $line =~ /\s+\S+\/(\S+\.cp)\@/ || $line =~ /\s+\S+\/(\S+\.cpp)\@/ ) {
                 push (@chStringArray, $1) if (!grep /$1/, @chStringArray);
                 $chFile = $1;
             }
		#print "\nLine - \n";
		#print $line;
             if ($line =~ /[\*]{15}([*\s[a-zA-Z0-9\_\(\)\*\{]+)/ ) {
#		print "\n Entering Regex";
                if (!grep /\Q$1\E/, @fnStringArray)
                 {
                     $fn = $1;
                     addValue($chFile,$fn);
                 }
                 push (@fnStringArray, $1) if (!grep /\Q$1\E/, @fnStringArray);
             }
         }
    }
    foreach (@chStringArray)
    {
        $key = $_;
        foreach ($h{$key})
        {
    	    $sth = $dbh->prepare("UPDATE code_coverage SET functionInFile='$_' where FileName='$key'");
            $sth->execute();
        }
    }
    if (@chStringArray) {
        $chString = join(",", @chStringArray);
        push(@arraySt, $chString);
#        print "\n\$chString \n $chString\n";
    }
	#else {
        # $chString = "No .c/.h files impacted";
       # #$chString = "fem_rim.c";
    # }
    if (@fnStringArray) {
        $fnString = join(",", @fnStringArray);
    }
    $sth = $dbh->prepare("UPDATE Mapping SET chfiles='$chString' where Bug_Id='$eachBugIds' and username='$username'");
    $sth->execute();
#    @chStringArray = ();
#    @fnStringArray = ();
    @chStringArray = ();
    @fnStringArray = ();
}
$str_array=join(',',@arraySt);
print $str_array;
#print $chString , $fnString;
#print $fnString;
#my $count = @bugArray;
#print "Total Bug Ids are $count\n";
#my $buildpath = '/ws/gsbu-build36/CBAS/archive/flo_dsgs7-classic/dev/bin/';
#my @output = `ls -ltr /ws/gsbu-build36/CBAS/archive/flo_dsgs7-classic/dev/bin/`;
#my $finalImage;
#foreach my $path(@output) {
#    next if ($path =~ /^total/);
#    my @pathSplit = split(" ", $path);
#    my $lastPath = $pathSplit[scalar @pathSplit -1];
#    my @bugOutPut = `ls -ltr $buildpath/$lastPath/$platform-universalk9*.bin`;
#    foreach my $image (@bugOutPut) {
#       my @totalImage = split("-> ", $image);
#       $finalImage = $totalImage[$#totalImage];
#      # $sth = $dbh->prepare("INSERT INTO image VALUES ('$platform','$finalImage','$username')");
#       #$sth->execute();
#}
#}
#       $sth = $dbh->prepare("DELETE FROM image WHERE username='$username'");
#       $sth->execute();
#       $sth = $dbh->prepare("INSERT INTO image VALUES ('$platform','$finalImage','$username')");
#       $sth->execute();
#print "\n\n\n final uimage : $finalImage\n";
$sth->finish;
$dbh->disconnect;
=cut

=pod
#from here
sub uniq {
    my %seen;
    grep !$seen{$_}++, @_;
}
my @filtered = uniq(@bugArray);
foreach my $eachBugIds (@filtered)
{
#print $eachBugIds;
my $url = "http://prrq.cloudapps.cisco.com/prrq/viewReview.do?bugId=$eachBugIds";
my $mech = WWW::Mechanize->new();
$mech->cookie_jar(HTTP::Cookies->new());
$mech->get($url);
$mech->form_id("login-form");
$mech->field("userid", "satkommu");
$mech->field("password", "Nanna123\$");
$mech->click;
my $status = 0;
my @output_page = $mech->content();
#print @output_page;
if ((grep /"Navigation Error"/, @output_page) || ((grep/"Welcome,"/, @output_page) && (grep/"Log Out"/, @output_page))) {
    print "i am logged in\n";
    $status = 1;
}
foreach my $line (@output_page)
{
    #print "\n***********************";
	my @lineArray = split /\n/,$line;
	foreach my $eachLine (@lineArray)
	{
		if ($eachLine =~ /Contact Information for (.*)\</) {
			#print "Entering loop";
			#print $1;
			my $url="http://prrq.cloudapps.cisco.com/prrq/viewReview.do?action=show_diff&bugId=$eachBugIds&queueName=$1";
			my $mech = WWW::Mechanize->new();
			$mech->cookie_jar(HTTP::Cookies->new());
			$mech->get($url);
			$mech->form_id("login-form");
			$mech->field("userid", "satkommu");
			$mech->field("password", "Nanna123\$");
			$mech->click;
			my $status = 0;
			my @output_prrq = $mech->content();
			#print @output_page;
			if ((grep /"Navigation Error"/, @output_prrq) || ((grep/"Welcome,"/, @output_prrq) && (grep/"Log Out"/, @output_prrq))) {
				print "i am logged in\n";
		  $status = 1;
			}
			#print @output_prrq;
			foreach my $line_prrq (@output_prrq)
			{
				my @lineArray_prrq = split /\n/,$line_prrq;
				#my $n=1;
				my $temp="";
				my $step=1;
				my $str="";
				my $each="";
				foreach my $eachLine_prrq (@lineArray_prrq)
				{
				#print $n;
				#print "---->";
				#$n=$n+1;
				#print "$eachLine_prrq \n";
					if ($eachLine_prrq =~ /Index:\s+([a-zA-Z0-9_\/]+)[\/](\S+\.c)\</ || $eachLine_prrq =~ /Index:\s+([a-zA-Z0-9_\/]+)[\/](\S+\.h)\</ || $eachLine_prrq =~ /Index:\s+([a-zA-Z0-9_\/]+)[\/](\S+\.cpp)\</|| $eachLine_prrq =~ /Index:\s+([a-zA-Z0-9_\/]+)[\/](\S+\.cp)\</|| $eachLine_prrq =~ /Index:\s+([a-zA-Z0-9_\/]+)[\/](\S+\.cc)\</)
					{
					$temp="";
					print "$1--->$2\n";
					}
			        if ($step==2)
					{
						$eachLine_prrq=$temp.$eachLine_prrq;
						#print "$eachLine_prrq\n";
						if ($eachLine_prrq =~ /Component:\s*(\S*)/)
						{
						    $str=$1;
							#print "****$eachLine_prrq\n*****";
							 if ($eachLine_prrq =~ /([(][a-zA-Z]+[-][a-zA-Z]+[-][0-9]+:.*[)])/)
							{
								$each=$1;
							$str=$str.$each;
							print "$str\n";
							}
							#print "$1\n";
							$step=1;
							next;
						}
					}
					if ($eachLine_prrq =~ /Component:\s*(\S*)/)
					{
						$temp=$eachLine_prrq;
						$step=$step+1;
					}
				#if ($eachLine_prrq =~ /\s+\S+\/(\S+\.c)\@/ || $eachLine_prrq =~ /\s+\S+\/(\S+\.h)\@/ || $eachLine_prrq =~ /\s+\S+\/(\S+\.cp)\@/ || $eachLine_prrq =~ /\s+\S+\/(\S+\.cpp)\@/) {
				#	print "$1\n";
				#}
				}
			}
		}
	}
}
}
=cut


sub uniq {
    my %seen;
    grep !$seen{$_}++, @_;
}

my @filtered = uniq(@bugArray);
foreach my $eachBugIds (@filtered)
{
	$bugstring=$eachBugIds;
	push (@bugstringArray,$bugstring)if (!grep /$bugstring/, @bugstringArray);
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
				#addprrqValue($compString,$chFile);
				#print "$1--->$2\n";
				#$sth = $dbh->prepare("UPDATE code_coverage SET `Index`='$index' where FileName='$chFile'");
				#$sth->execute();
			}
			
			if ($eachLine_prrq =~ /Component:\s(\S+)@/)
			{
				#print $eachLine_prrq;
				$compString=$1;	
				push (@compStringArray, $compString) if (!grep /$compString/, @compStringArray);
				#print $eachBugIds;
				addbugid_component($eachBugIds,$compString);
				
				
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
	
}

print "++++";
print "$_," for keys %g;

#print "{keys %g }+";

print "++++";
#print $bugstring;
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
