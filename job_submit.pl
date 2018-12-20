#!/usr/bin/perl
#!/usr/bin/expect
use strict;
use warnings;
use Data::Dumper; #To display data.
#use Date::Parse; #Converting date/time format to unix timestamp
use DBI;
use Expect;
#use WWW::Mechanize;

my $sanity_tb1 = new Expect();
my $jobs = $ARGV[0];
#my $imgpth = $ARGV[1];
#my $lineup = $ARGV[2];
my @CFD_Array = split /,/,$jobs;
#my $final ="";
#my @final_array;
my $host="sjc-dbdl-mysql4";
my $database="irs";
my $userid = "irs";
my $password = "irs";
my $dbh = DBI->connect("DBI:mysql:database=$database;host=$host;",$userid, $password)  or die "Could not connect to database: $DBI::errstr";
my($flex_path,$flex_env,$lang,$tb,$server);
my $flex_job="";
my $vpws_job="";
my $lag_job="";
my $sth;
my $path="";
my $env="";
my $return;
my $result;
#my @job_q;
my %job_q=();
sub job_run_python
{
  my $job = shift;
  #print ("$job\n");
	if($job ne "")
{		my $job_ex.=$job.' -mailto "amankum4"';
		my $timeout=3600;
		my $command1="cd $path\n";
		$sanity_tb1->send("$command1\n");
		$sanity_tb1->expect($timeout,'$') or die "Din't get the prompt";
		my $command2="csh";
		$sanity_tb1->send("$command2\n");
		$sanity_tb1->expect($timeout,'$') or die "Din't get the prompt";
		my $command3="source $env";
		#print("$command3\n");
		$sanity_tb1->send("$command3\n");
		$sanity_tb1->expect($timeout,'$') or die "Din't get the prompt";
		
		#print $job_ex;
		my $command4=("$job_ex");	
		$sanity_tb1->send("$command4\n");
		$sanity_tb1->expect($timeout,'$') or die "Din't get the prompt";
=pod
		my $out = $sanity_tb1->exp_before();
		my @array = split /\n/,$out;
		#print("****@array***");
		foreach (1..$#array) { 
           if ($array[$_] =~ /(Run completed. Cleaning up and removing job directory)/){
		   
				$return= $1;
				
		   
		   }
		   
        }
		return $return;
=cut
}





}
foreach $a (@CFD_Array){
my $sql = "SELECT * from job_suit where job='$a'";
$sth = $dbh->prepare($sql);
$sth->execute();
	while (my @row = $sth->fetchrow_array){
	 my $r0=$row[0]; #suite
	 my $r1=$row[2]; #path
	 my $r2=$row[3]; #env
	 my $r3=$row[4]; #lang
	 my $r4=$row[5]; #tb
	 my $r5=$row[6]; #server
	 #print("$r0\n$r1\n$r2\n$r3\n$r4\n$r5");
	if($row[0] eq "Flex" and $row[4] eq "python" and $row[5] eq "Gamma")
	{
		if($flex_job eq "")
		{
			$flex_job.="python -m ats.easypy $a -tf TOPOLOGY.scapa_5rtr_tgn";
		}
		else
		{
			$flex_job.=';'."python -m ats.easypy $a -tf TOPOLOGY.scapa_5rtr_tgn";
		}
	}
	elsif($row[0] eq "vpws"and $row[4] eq "python" and $row[5] eq "Gamma")
	{
	   if ($vpws_job eq "")
	   {
			$vpws_job.="python -m ats.easypy $a -tf TOPOLOGY.scapa_5rtr_tgn";
		}
		else
		{
			$vpws_job.=';'."python -m ats.easypy $a -tf TOPOLOGY.scapa_5rtr_tgn";
		}
	}
	elsif($row[0] eq "lag"and $row[4] eq "python" and $row[5] eq "Gamma")
	{
		if ($lag_job eq "")
	   {
			$lag_job.="python -m ats.easypy $a -tf TOPOLOGY.scapa_5rtr_tgn";
		}
		else
		{
			$lag_job.=';'."python -m ats.easypy $a -tf TOPOLOGY.scapa_5rtr_tgn";
		}
	}
	
  }
  
 }	
%job_q =('flex_job', $flex_job, 'lag_job', $lag_job, 'vpws_job', $vpws_job);
$sanity_tb1->spawn('ssh amankum4@bgl-ads-144') or die "Cannot spawn sftp command \n";
$sanity_tb1->expect(150, ["password:"]);
$sanity_tb1->send("Godaan60@\n");
$sanity_tb1->expect(150,'$') or die "Din't get the prompt";
foreach my $key (keys %job_q) {
#print("$key\n");
#print ("$job_q{$key}\n");
		

if ($key eq "flex_job")
{
	$path="/ws/gpandi-bgl/Scapa/flex_lsp_May2017/june15";
	$env="activate_irfan.csh";
}
elsif($key eq "vpws_job")
{
	$path="/ws/gpandi-bgl/Scapa/VPWS";
	$env="activate_CFM.csh";
}
else
{
	$path="/ws/gpandi-bgl/Scapa/LAG";
	$env="/ws/gpandi-bgl/Scapa/VPWS/activate_CFM.csh";
	
}
#print ($job_q{$key});
job_run_python($job_q{$key});

=pod

#print("$path\n$env");
if($job_q{$key} ne "")
{

my $command1="cd $path\n";
$sanity_tb1->send("$command1\n");
sleep(5);
$sanity_tb1->expect(150,'$') or die "Din't get the prompt";
my $command2="csh ";
$sanity_tb1->send("$command2\n");
sleep(5);
$sanity_tb1->expect(150,'$') or die "Din't get the prompt";
my $command3="source $env ";
#print("$command3\n");
$sanity_tb1->send("$command3\n");
sleep(10);
$sanity_tb1->expect(150,'$') or die "Din't get the prompt";
my $command4=("$job_q{$key} ");
#print("$command4\n");
$sanity_tb1->send("$command4\n");
$sanity_tb1->expect(150,'$') or die "Din't get the prompt";

my $out = $sanity_tb1->exp_before();
my @array = split /\n/,$out;
#print @array;
foreach (1..$#array) {
	 if ($array[$_] =~ //) 
	{

	
	 
	 
	}
  }
	 
}


 #python -m ats.easypy S_Flex_LSP_BO_LANPHY_OSPF_MainIntf_job2.py -tf TOPOLOGY.scapa_5rtr_tgn ; python -m ats.easypy S_Flex_LSP_40G_GFPF_ISIS_MainIntf_job3.py -tf TOPOLOGY.scapa_5rtr_tgn ; python -m ats.easypy S_Flex_LSP_CH2_GFPF_ISIS_SubIntf_job4.py -tf TOPOLOGY.scapa_5rtr_tgn ; python -m ats.easypy S_Flex_LSP_100G_GMP_ISIS_SubIntf_job5.py -tf TOPOLOGY.scapa_5rtr_tgn ; python -m ats.easypy S_Flex_LSP_BO_BMP_OSPF_MainIntf_job6.py -tf TOPOLOGY.scapa_5rtr_tgn
$path="";
$env="";
=cut
}


#print("\n$flex_job\n$lag_job\n$vpws_job");
$sanity_tb1->hard_close();
$sth->finish;
$dbh->disconnect;

