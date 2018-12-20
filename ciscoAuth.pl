use strict;
use warnings;
use strict;
use WWW::Mechanize;
use HTTP::Cookies;
use Data::Dumper;
#my $outfile = "authentication1.html";
my $url = "https://sso.cisco.com/autho/forms/CDClogin.htm";
#my $username = "ddileepr";
#my $password = "Paxdil123(*";
my $username = $ARGV[0];
my $password = $ARGV[1];
my $mech = WWW::Mechanize->new();
$mech->cookie_jar(HTTP::Cookies->new());
$mech->get($url);
$mech->form_id("login-form");
$mech->field("userid", $username);
$mech->field("password", $password);
$mech->click; 
my $status = 0;
my @output_page = $mech->content();
print @output_page;
if ((grep /"Navigation Error"/, @output_page) || ((grep/"Welcome,"/, @output_page) && (grep/"Log Out"/, @output_page))) {
    print "i am logged in\n";
    $status = 1;
}
print $status;
#exit $status;
#open(OUTFILE, ">$outfile");
#binmode(OUTFILE, ":utf8");
#print OUTFILE "$output_page";
#close(OUTFILE);

