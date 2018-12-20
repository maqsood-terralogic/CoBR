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

my $branch = $ARGV[1];

#my $branch = $ARGV[2];

print "Current database is $database\n";

my ($testcase_string);

my $userid = "irs";

my $password = "irs";

my $dbh = DBI->connect("DBI:mysql:database=$database;host=$host;",$userid, $password)  or die "Could not connect to database: $DBI::errstr";
					   
$sanity_tb1->spawn('ssh satkommu@scapa-ind01-lnx') or die "Cannot spawn sftp command \n";

$sanity_tb1->expect(100, ["password:"]);

$sanity_tb1->send("Kommu123\$\n");

$sanity_tb1->expect(200,'-re','[#>$]') or die "Din't get the prompt";

$testcase_string="";
	#my $sql = "SELECT $branch from Component_script where Component='packet'";
	my $sql = "SELECT $branch from Component_script";
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
	
print "\n";
print $testcase_string;
print "\n";
#print "$comp";
$sth->finish;
$dbh->disconnect;
$sanity_tb1->hard_close();