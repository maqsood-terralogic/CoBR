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

my $component = $ARGV[1];

my $branch = $ARGV[2];

#my $branch = $ARGV[2];

print "Current database is $database\n";

my ($packet,$infra,$otn,$testcase_string);

my $userid = "irs";

my $password = "irs";

my $dbh = DBI->connect("DBI:mysql:database=$database;host=$host;",$userid, $password)  or die "Could not connect to database: $DBI::errstr";
					   
$sanity_tb1->spawn('ssh satkommu@scapa-ind01-lnx') or die "Cannot spawn sftp command \n";

$sanity_tb1->expect(100, ["password:"]);

$sanity_tb1->send("Kommu123\$\n");

$sanity_tb1->expect(200,'-re','[#>$]') or die "Din't get the prompt";
 my $comp="";
   
my $sql = "SELECT job_file from component_job where component_name ='$component'";
my $sth = $dbh->prepare($sql);

$sth->execute();
$testcase_string="";
while (my @row = $sth->fetchrow_array) {
		$testcase_string =$row[0];
	}
=pod
while (my @row = $sth->fetchrow_array) {
   $packet=$row[1];
   $infra= $row[2];
   $otn=$row[3];
}
$testcase_string="";
if ($packet eq 'YES')
{  
	$comp="packet";
	#my $sql = "SELECT $branch from Component_script where Component='packet'";
	my $sql = "SELECT $branch from Component_script where Component='packet_test'";
	my $sth = $dbh->prepare($sql);
	$sth->execute();
	while (my @row = $sth->fetchrow_array) {
		$testcase_string.=$row[0];
	}
}
if ($infra eq "YES")
{
    $comp.="infra";
	#my $sql = "SELECT $branch from Component_script where Component='infra'";
	my $sql = "SELECT $branch from Component_script where Component='infra'";
	my $sth = $dbh->prepare($sql);
	$sth->execute();
	while (my @row = $sth->fetchrow_array) {
		if ($testcase_string eq "")
		{
			$testcase_string.=$row[0];
		}
		else
		{
			$testcase_string.=','.$row[0];
		}
	}
}
if ($otn eq "YES")
{   
	$comp.="otn";
	#my $sql = "SELECT $branch from Component_script where Component='otn'";
	my $sql = "SELECT $branch from Component_script where Component='otn'";
	my $sth = $dbh->prepare($sql);
	$sth->execute();
	while (my @row = $sth->fetchrow_array) {
		if ($testcase_string eq "")
		{
			$testcase_string.=$row[0];
		}
		else
		{
			$testcase_string.=','.$row[0];
		}
	}
}
=cut
print "\n";
print $testcase_string;
print "\n";
#print "$comp";
$sth->finish;
$dbh->disconnect;
$sanity_tb1->hard_close();