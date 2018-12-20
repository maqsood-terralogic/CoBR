#!/usr/bin/perl
use strict;
use warnings;
use Data::Dumper; #To display data.
use Date::Parse; #Converting date/time format to unix timestamp
use DBI;
use Expect;
use WWW::Mechanize;

my $mech = WWW::Mechanize->new();
$mech->cookie_jar(HTTP::Cookies->new());
my $from_efr=$ARGV[0];
my $to_efr=$ARGV[1];
my @bugArray;
my @compArray;
#print "PCR_ARRAY:@pcrNos";
   # my @bugArray;
	 my $pim_url="http://pims-web.cisco.com/pims-home/fcgi-bin/BugReport/DDTS.cgi?Function=DDTS&from_prod_build=$from_efr&to_prod_build=$to_efr&by+prod_bld.x=6&by+prod_bld.y=12";
        $mech->credentials("amankum4", "Godaan60@");
        $mech->get($pim_url);
		sleep(5);
        my $response = $mech->response();
        my @content = split /\n/,$response->{_content};
		#print @content;
        foreach my $line (@content) {
            if($line=~/(CSC\w+\d+)/){
                push(@bugArray,$1);
            }
			if($line=~/<td>(\S+)<\/td><td>\S+<\/td><td>\S+<\/td><td>\S+<\/td>/){
				push (@compArray,$1);
			
			
			
			}
        }
   
print "----";   
print "@bugArray";
#print "@bugArray";
print"****";
print "@compArray";
print"+";
