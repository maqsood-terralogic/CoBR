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
my @efrNos = map defined( $_ ) ? $_ : '', split /,/,$ARGV[0];
my @pcr_array;
foreach my $efr_no (@efrNos){
    #my @pcr_array;
    my @temp;
    if ($efr_no ne ' '){
        my $url="http://pi-web.cisco.com/pims-home/fcgi-bin/EngFeature/Nov2k/EngFeature.cgi?Function=EngFeatureMain&action=viewqueue&efrid=$efr_no&req=search&type=detail";
        $mech->credentials("rakdomma", "Spirent!12345");
        $mech->get($url);
        my $response = $mech->response();
        my @content = split /\n/,$response->{_content};
        foreach my $line (@content) {
            #print "Line match $line\n";
            @temp = $line=~/(PCR-\d+)/g;
            push (@pcr_array,@temp);
            
         }
    }
 #   print "@pcr_array";
}
print "@pcr_array";
