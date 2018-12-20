<?php
    session_start();
    include("dbconfig.php");
    $username = $_SESSION['username'];
    $EFRNumber = $_SESSION['EFRNumber'];
	$tc=$_SESSION["testcases_option"];
	//echo $tc;
	if($tc == "component")
	{
		$info=$_SESSION["testSuite"];
	}
	else if($tc == "sanity")
	{
		$info=$_SESSION["complete_sanity_testcases"];
	}
	//echo $info;
    $os = array_unique(explode(',', $info));
    $a = array("arwen_hqos_dynamic_arp_modified.tcl"=>"arwen_hqos_dynamic_arp_modified.job",
        "policymap.py"=>"policymap.py",
        "l3_policy_output.tcl"=>"l3_policy_output.job",
        "l2_service_policy.tcl"=>"l2_service_policy.job",
        "show_policy_map.tcl"=>"show_policy_map.job",
        "l3_service_policy.tcl"=>"l3_service_policy.job",
        "sub_interface_policy_check.tcl"=>"sub_interface_policy_check.job",
        "class_map_check.tcl"=>"class_map_check.job",
        "mcast_igmp_basic_cli.py" => "mcast_igmp_basic_cli_job.py",
        "mcast_PIMv4_basic_cli.py" => "mcast_PIMv4_basic_cli_job.py",
        "mcast_msdp_basic_cli.py"  => "mcast_msdp_basic_cli_job.py",
        "mcast_PIMv6_basic_cli.py" => "mcast_PIMv6_basic_cli_job.py",
    );

    $lineup = "NULL";
    $imgpth = "NULL";

    $stack = array();
    $keys = array();
    foreach($os as $value)
    {
        if (array_key_exists($value,$a))
        {
            array_push($stack,$a[$value]);
            array_push($keys,$value);
        }
    }
    $jobs = implode(",",$stack);
    $keys_value = implode(",",$keys);
    if($username==""){
        header("Location: index.php");
    }
    $count = sizeof($keys);
    if(!empty($keys_value)){
        foreach(explode(",",$jobs) as $str)
        {
            $testSuite=trim($str,":,");
            date_default_timezone_set('Asia/Kolkata');
            $dt = new DateTime();
            $Time = $dt->format('Y-m-d H:i:s');
            $sql =  "INSERT INTO jobstatus (username,jobid,time_date,jobstatus) values('$username','$testSuite','$Time','Not Started')";
            $retval = mysql_query( $sql, $conn );
            if(! $retval )
            {
                die('Could not enter data: ' . mysql_error());
            }
        }
        $jobs = implode(",", $stack);
        $num1 = array();
        $values= array();
        $sql1 = "SELECT jobid,id from irs.jobstatus ORDER BY id DESC LIMIT $count";
        $retval = mysql_query($sql1, $conn);
        while ($row = mysql_fetch_array($retval, MYSQL_ASSOC)) {
            $num1[] = $row['id'];
            $values[]=  $row['jobid'];
        }
        if (!$retval) {
            die('Could not enter data: '.mysql_error());
        }
        $numbers = implode(",", $num1);
    }
	#print($info);
?>

<?php include "header.php" ?>
      <div class="main_container">
        <div class="col-md-3 left_col">
		  <div class="left_col scroll-view" style="margin-top: 85px;">
			<div class="clearfix"></div>
			<br />
			<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
			  <div class="menu_section">
				<ul class="nav side-menu">
				<!--
				 <li><a href="jobstatus.php"><i class="fa fa-server icon_nav"></i> Job Status</a></li> -->
				 <li><a href="faq.php"><i class="fa fa-question-circle icon_nav"></i>  FAQ's</a></li>
				 <li><a><i class="fa fa-address-card icon_nav"></i> Contact</a></li>
				</ul>
			  </div>

			</div>


		  </div>
		</div>


        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->


		  <section style="padding-top: 100px;">
          <!-- /top tiles -->
		  <div class="container">
           <div class="row">
				
			   <div class="col-md-12 col-sm-12 col-xs-12">
			   <form action="">
                <div class="x_panel_cisco">
                  <div class="x_content">
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action order-table">
                        <thead>
                          <tr class="headings">
                            <th class="column-title" style="text-align:  center;">Triggered Test Cases</th>
                          </tr>
                        </thead>

                        <tbody>
                            <?php
							 
                                if (empty($os)) {
                                    echo  '<tr>
                                        <td>No jobs are triggered</td>
                                        </tr>';
                                }else{
                                    foreach($os as $id){
                                        echo '<tr class="even pointer">';
                                        echo '<td class=" " style="text-align:center">'.$id.'</td>';
                                        echo '</tr>';
                                    }
                                    echo  '<h4 style="text-align:  center; color: #075071;">Note: To View the status of triggered Test Cases, Please Click "<b>JOB STATUS</b>"</h4><br>';
                                }
                            ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
				</form>

              </div>
		   </div>

         </div>

         </section>
        <!-- /page content -->
</div>

      </div>



	<!--Model popup-->


	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
		<div class="modal-header">
		<h4 class="modal-title">Configuration File</h4>
		</div>
		<div class="modal-body">
		<label class="control control-checkbox">
			<input type="checkbox" name="typecheck1" value="manual" id="manualRadio" required />&nbsp;&nbsp; Manual <i>(Step by Step selection of bugs and impacted source files)</i>
		</label><br><br>

		<label class="control control-checkbox" style="font-weight:600;">
			 Range of Code Coverage percentage <i>(Impacted Testcases within this range will only be selected. Multiple range selection is allowed)</i></i><br><br>
			<input type="checkbox" value="1%-25%" id="range1" onclick="setRange()"/> &nbsp;1%-25%<br>
			<input type="checkbox" value="25%-50%" id="range2" onclick="setRange()"/>&nbsp; 26%-50%<br>
			<input type="checkbox" value="50%-75%" id="range3" onclick="setRange()"/>&nbsp; 51%-75%<br>
			<input type="checkbox" value="75%-100%" id="range4" checked  onclick="setRange()"/> &nbsp;76%-100%<br>
			<input type="checkbox" value="91%-100%" id="range5"  onclick="setRange()"/>&nbsp; 91%-100%

		</label><br>
		</div>
		<div class="modal-footer">
		<button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
		</div>
		</div>

		</div>
		</div>
    </div>

   <!-- jQuery -->
    <script src="jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="js/custom.js"></script>

  </body>
</html>
<?php
    $jobs = implode(",",$stack);
    //echo $count;
    $_SESSION["ranTestCases"] = $info;
	$perl_result="";
    #exec("perl job.pl $jobs $numbers");
    #exec("stdbuf -oL nohup perl job.pl  &> log.log");
	$imgpth=$_SESSION["imgpth"];
	$lineup=$_SESSION["lineup"];
    #exec("stdbuf -oL nohup perl job.pl $jobs $numbers &> log.log");
	#print($info);
    //shell_exec("stdbuf -oL nohup perl job_earms.pl $info $imgpth $lineup > log.log");
	
	/*if($info != ""){
		ob_start();
		echo '
			<div id="preloader">
				<div id="status">
					<div class="progress1">
						<p style="text-align:center;">Executing jobs :: </p>
						<div class="circle done">
							<span class="label">1</span>
							<span class="title">  &nbsp; &nbsp;Contacting Server</span>
						</div>
						<span class="bar done"></span>
						<div class="circle done">
							<span class="label">2</span>
							<span class="title">  &nbsp; &nbsp;Jobs are in queue</span>
						</div>
						<span class="bar half"></span>
						<div class="circle active">
							<span class="label">3</span>
							<span class="title">  &nbsp; &nbsp;executed</span>
						</div>
					</div>
				</div>
			</div>

			<style>
				#preloader {
					position: fixed;
					top: 0;
					left: 0;
					right: 0;
					bottom: 0;
					background-color: #fff;
					z-index: 9999;
				}
				.progress1 {
					margin: 250px auto;
					text-align: center;
				}
				.progress1 .circle,
				.progress1 .bar {
					display: inline-block;
					background: #fff;
					padding: 10px;
					border-radius: 4px;
					border: 1.3px solid rgb(9, 154, 218);
				}
				.progress1 .bar {
					position: relative;
					width: 80px;
					height: 6px;
					top: 5px;
					margin-left: -5px;
					margin-right: -5px;
					border-left: none;
					border-right: none;
					border-radius: 0;
				}
				.progress1 .circle .label {
					display: inline-block;
					width: 32px;
					height: 32px;
					line-height: 25px;
					border-radius: 32px;
					margin-top: 3px;
					color: #b5b5ba;
					font-size: 17px;
				}
				.progress1 .circle .title {
					color: rgb(9, 154, 218);
					font-size: 13px;
					line-height: 30px;
					margin-left: -5px;
				}
				.progress1 .bar.done,
				.progress1 .circle.done {
					background: #eee;
				}
				.progress1 .bar.active {
					background: linear-gradient(to right, #EEE 40%, #FFF 60%);
				}
				.progress1 .circle.done .label {
					color: #FFF;
					background: #8bc435;
					box-shadow: inset 0 0 2px rgba(0,0,0,.2);
				}
				.progress1 .circle.done .title {
					color: #444;
				}
				.progress1 .circle.active .label {
					color: #FFF;
					background: #0c95be;
					box-shadow: inset 0 0 2px rgba(0,0,0,.2);
				}
				.progress1 .circle.active .title {
					color: #0c95be;
				}
			</style>
			<script src="jquery/dist/jquery.min.js"></script>
			<script>
				var i = 1;
				$(".progress1 .circle").removeClass().addClass("circle");
				$(".progress1 .bar").removeClass().addClass("bar");
				$(".progress1 .circle:nth-of-type(" + i + ")").addClass("active");

				$(".progress1 .circle:nth-of-type(" + (i-1) + ")").removeClass("active").addClass("done");

				$(".progress1 .circle:nth-of-type(" + (i-1) + ") .label").html("&#10003;");

				$(".progress1 .bar:nth-of-type(" + (i-1) + ")").addClass("active");

				$(".progress1 .bar:nth-of-type(" + (i-2) + ")").removeClass("active").addClass("done");

				i++;

				if (i==0) {
					$(".progress1 .bar").removeClass().addClass("bar");
					$(".progress1 div.circle").removeClass().addClass("circle");
					i = 1;
				}
			</script>
		';
		ob_end_flush();
		ob_flush();
		flush();
	}*/
	#print_r($os);
	 #print("***$info");
	#exec("perl job_submit.pl $info &",$perl_result);
	#$part1=implode(',',$perl_result);
	
	
    echo $output;
?>
