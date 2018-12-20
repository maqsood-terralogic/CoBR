#!/usr/bin/perl
#!/usr/bin/expect
use strict;
use warnings;
#use Data::Dumper; #To display data.
#use Date::Parse; #Converting date/time format to unix timestamp
#use DBI;
use Expect;
#use WWW::Mechanize;

my $sanity_tb1 = new Expect();
my $jobs = $ARGV[0];
my $imgpth = $ARGV[1];
my $lineup = $ARGV[2];
my @CFD_Array = split /,/,$jobs;
my $final ="";
my @final_array;
foreach $a (@CFD_Array){
   $final = $final." ".$a;
}
#$final = $final."\\\"";
#print "$final\n";
$sanity_tb1->spawn('ssh satkommu@scapa-ind01-lnx') or die "Cannot spawn sftp command \n";
$sanity_tb1->expect(100, ["password:"]);
$sanity_tb1->send("Kommu123\$\n");
$sanity_tb1->expect(100,'$') or die "Din't get the prompt";
sleep 10;
#my $command1 = "cd /users/satkommu/scapa/arwen/scapa_arwen_l2_sanity/cobr\n";
my $command1 = "cd /users/satkommu/scapa/cobr\n";
$sanity_tb1->send($command1);
$sanity_tb1->expect(150,'$') or die "Din't get the prompt";
my $val = "echo set test_id \\\"".$final."\\\" > testcase.tcl\n";
my $command2 = $val;
$sanity_tb1->send($command2);

#changing the image in scapa_earms_submit_1.cfg1348103086
$sanity_tb1->expect(150,'$') or die "Din't get the prompt";
my $command4 = "perl update_image.pl $imgpth $lineup\n";
$sanity_tb1->send($command4);

$sanity_tb1->expect(150,'$') or die "Din't get the prompt";
#my $command3 = "/auto/earmsdata/Earms-2/earms_cli/earms run /users/satkommu/scapa/arwen/scapa_arwen_l2_sanity/cobr/scapa_earms_submit_1.cfg1348103086\n";
my $command3 = "/auto/earmsdata/Earms-2/earms_cli/earms run /users/satkommu/scapa/cobr/scapa_earms_submit_1.cfg1348103086\n";
$sanity_tb1->send($command3);
$sanity_tb1->expect(150,'$') or die "Din't get the prompt";
#sleep 200;
print "\n================final end\n";
$sanity_tb1->hard_close();
#my $command3 = "python ats_runinfo.py $final $ids &\n";
#my $command4 = "python pyats_runinfo.py $final $ids &\n";
#if ($final =~ m/.py/) {
#$sanity_tb1->send($command4);
#$sanity_tb1->expect(15,'-re','#|>') or die "Din't get the prompt";
#}
#my $command5 = "python monitor.py $final $ids &\n";
#$sanity_tb1->send($command2);
#$sanity_tb1->expect(90000,'-re','sjc-xdm-123:') or die "Din't get the prompt";
#$sanity_tb1->hard_close();$sanity_tb1->send($command2);
#$sanity_tb1->expect(90000,'-re','sjc-xdm-123:') or die "Din't get the prompt";
#$sanity_tb1->hard_close();$sanity_tb1->send($command2);
#$sanity_tb1->expect(90000,'-re','sjc-xdm-123:') or die "Din't get the prompt";
#$sanity_tb1->hard_close();






=head
#Changes done temporarily. Remove this section after successful execution
#!/usr/bin/perl
#!/usr/bin/expect
use strict;
use warnings;
#use Data::Dumper; #To display data.
#use Date::Parse; #Converting date/time format to unix timestamp
#use DBI;
use Expect;
#use WWW::Mechanize;
=head
my $sanity_tb1 = new Expect();
#my $jobs = $ARGV[0];
#my @CFD_Array = split /,/,$jobs;
my $final ="";
my @final_array;
#foreach $a (@CFD_Array){
   $final = $final." ".$a;
}
#$final = $final."\\\"";
#print "$final\n";
`ssh satkommu\@scapa-ind01-lnx\:Nanna123\$`;
print "Sleeping";
sleep 3;
`Nanna123\$\n"`;
system("ls\n");
#$sanity_tb1->spawn('ssh satkommu@scapa-ind01-lnx') or die "Cannot spawn sftp command \n";
$sanity_tb1->expect(100, ["password:"]);
$sanity_tb1->send("Nanna123\$\n");
$sanity_tb1->expect(100,'$') or die "Din't get the prompt";
sleep 10;
#my $command1 = "cd /users/satkommu/scapa/arwen/scapa_arwen_l2_sanity/cobr\n";
my $command1 = "cd /users/satkommu/scapa/cobr\n";
$sanity_tb1->send($command1);
$sanity_tb1->expect(150,'$') or die "Din't get the prompt";
=head
<<<
#$sanity_tb1->send("chmod 777 /users/satkommu/scapa/arwen/scapa_arwen_l2_sanity/cobr/scapa_earms_submit.cfg1348103086");
$sanity_tb1->send("chmod 777 /users/satkommu/scapa/cobr/scapa_earms_submit_1.cfg1348103086");
$sanity_tb1->expect(150,'$') or die "Din't get the prompt";

#open /users/satkommu/scapa/arwen/scapa_arwen_l2_sanity/cobr/scapa_earms_submit.cfg1348103086
open /users/satkommu/scapa/cobr/scapa_earms_submit_1.cfg1348103086
search using regex argv[0]
if present{
replace the value with regex
}
>>>
my $val = "echo set test_id \\\"".$final."\\\" > testcase.tcl\n";
my $command2 = $val;
$sanity_tb1->send($command2);
$sanity_tb1->expect(150,'$') or die "Din't get the prompt";
#my $command3 = "/auto/earmsdata/Earms-2/earms_cli/earms run /users/satkommu/scapa/arwen/scapa_arwen_l2_sanity/cobr/scapa_earms_submit.cfg1348103086\n";
my $command3 = "/auto/earmsdata/Earms-2/earms_cli/earms run /users/satkommu/scapa/cobr/scapa_earms_submit_1.cfg1348103086\n";
$sanity_tb1->send($command3);
$sanity_tb1->expect(150,'$') or die "Din't get the prompt";
#sleep 200;
print "\n================final end\n";
$sanity_tb1->hard_close();

#my $command3 = "python ats_runinfo.py $final $ids &\n";
#my $command4 = "python pyats_runinfo.py $final $ids &\n";
#if ($final =~ m/.py/) {
#$sanity_tb1->send($command4);
#$sanity_tb1->expect(15,'-re','#|>') or die "Din't get the prompt";
#}
#my $command5 = "python monitor.py $final $ids &\n";
#$sanity_tb1->send($command2);
#$sanity_tb1->expect(90000,'-re','sjc-xdm-123:') or die "Din't get the prompt";
#$sanity_tb1->hard_close();$sanity_tb1->send($command2);
#$sanity_tb1->expect(90000,'-re','sjc-xdm-123:') or die "Din't get the prompt";
#$sanity_tb1->hard_close();$sanity_tb1->send($command2);
#$sanity_tb1->expect(90000,'-re','sjc-xdm-123:') or die "Din't get the prompt";
#$sanity_tb1->hard_close();
=cut
