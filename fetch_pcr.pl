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
my @pcrNos = map defined( $_ ) ? $_ : '', split /,/,$ARGV[0];
my @bugArray;
#print "PCR_ARRAY:@pcrNos";
foreach my $pcr_value(@pcrNos){
   # my @bugArray;
    if($pcr_value ne ''){
        my $pcr_url="http://pi-web.cisco.com/pims-home/fcgi-bin/BugReport/DDTS.cgi?Function=DDTS&pcr_id=$pcr_value";
        $mech->credentials("rakdomma", "Spirent!12345");
        $mech->get($pcr_url);
        my $response = $mech->response();
        my @content = split /\n/,$response->{_content};
        foreach my $line (@content) {
            if($line=~/(CSC\w+\d+)/){
                push(@bugArray,$1);
            }
        }
    }
#print "@bugArray";
}
print "@bugArray";
