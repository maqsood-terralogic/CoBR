 <?php
    session_start();
    include("dbconfig.php");
	#echo $_SESSION["testcases_option"];
    $info = $_SESSION["testcases"];
	#print_r($info);
	$branch=$_SESSION["efr_branch"];
    $str = implode(",",array_unique(explode(',', $info)));
    #print $str;
    $username = $_SESSION["username"];
    if($username==""){
        header("Location: index.php");
    }

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

				 <li><a href="jobstatus.php"><i class="fa fa-server icon_nav"></i> Job Status</a></li>
				 <li><a href="faq.php"><i class="fa fa-question-circle icon_nav"></i>  FAQ's</a></li>
				 <li><a><i class="fa fa-id-card-o icon_nav"></i> Contact</a></li>
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
			 <div class="example">
			 <form class="form-inline" style="margin-left:17px;">
			<div class="form-group col-sm-4">
				 <a class="btn btn-primary" href="javascript:history.go(-1);" type="submit"> <i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;Back</a>
			   </div>
			   <div class="form-group col-sm-6">
				   <h2 for="inputInlineRemember">Selected Test Cases</h2>
			   </div>
			 </form>
		   </div>
			   <div class="col-md-12 col-sm-12 col-xs-12">
			   <form action="" id="utimsForm">
				<div class="x_panel_cisco">
				<input type="text" id="myInput" class="light-table-filter" data-table="order-table" placeholder="Search" style="float:left;margin-left: 6px">
				  <div class="x_content">
					<div class="table-responsive">
					  <table class="table table-striped jambo_table bulk_action order-table">
						<thead>
						  <tr class="headings">
							<th width="8%">
							  <input type="checkbox" id="check-all" class="flat"> Select
							</th>
							<th class="column-title">TestCase</th>
							<th class="column-title">TestBed</th>
							<th class="column-title">RunTime</th>
						  </tr>
						</thead>

						<tbody>
                            <?php
                                $i = 0;
								$stat=1;
								$job_count=0;
								$run_g=0;
								$run_z=0;
								$array2=array();
								$str = implode(',',array_unique(explode(',', $str)));
								#print $str;
                                foreach(explode(",",$str) as $str){
                    				$testSuite=trim($str,":,");
									#print("$testSuite\n");
									$testSuite = str_replace(' ', '', $testSuite);
									#print("$testSuite");
									$job="lag_vpws.py";
									if(!in_array($testSuite,$array2))
									{
									#print("**$testSuite**");
									#print_r($array2);
									if (($testSuite=="vpws_sauron_regression_job_sub.py" and (in_array($job,$array2))) or ($testSuite=="lag_vpws.py" and (in_array("vpws_sauron_regression_job_sub.py",$array2))))
									{ 
									  $sql=0;
			
									}
									
									else{
										
									if($branch=="MC"){
									$sql="SELECT * from mc_run_time where job_name='$testSuite'";
									}
									else{
									$sql="SELECT * from run_time where job_name='$testSuite'";
									}
									#print ("$sql\n");
									#$sql="SELECT * from run_time where job_name='$testSuite'";
									$retval = mysql_query($sql, $conn);
									#print("$retval\n");
                    				
                                    
									if($retval){
											$num_rows = mysql_num_rows($retval);
											if($row = mysql_fetch_array($retval, MYSQL_ASSOC)){
												$job_count++;
									echo '<tr class="even pointer">';
                    				echo '<td class="a-center " width="8%">';
                                    echo '<input type="checkbox" class="flat checkbox" name="table_records"/>';
                                    echo '</td>';
									echo '<td class="testSuite">'.$row["job_name"].'</td>';
                                    echo '<td class=" ">'.$row["testbed"].'</td>';
									echo '<td class=" ">'.$row["run_time"].'</td>';
										if ($row["testbed"]=="Gamma"){
										$run_g=$run_g+(int)$row["run_time"];
										$cobr_run_g=$run_g;
										}
										else {
											$run_z=$run_z+(int)$row["run_time"];
											$cobr_run_z=$run_z;
										}
											}
									}	
                                    echo '</tr>';
                                    $i++;
											
									array_push($array2,$testSuite);	
									
									}	
								}
							}
								#print_r($array2);
								#print($cobr_run);
								if ($cobr_run_g > $cobr_run_z){
									
									$cobr_run=$cobr_run_g;
								}
								else{
									$cobr_run=$cobr_run_z;
								}
								if ($branch=="MC")
								{
									echo'<h3 style="text-align:center;"><font color="blue">Cobr RunTime = '.$cobr_run.'hr,  </font> <font color="red">RI RunTime = 152hr</font></h3>';
								
								echo'<h3 style="text-align:center;"><font color="blue">Cobr jobs = '.$job_count.',  </font> <font color="red"> RI Jobs = 29</font></h3>';
								}
								
								else{
									
								echo'<h3 style="text-align:center;"><font color="blue">Cobr RunTime = '.$cobr_run.'hr,  </font> <font color="red">RI RunTime = 75hr</font></h3>';
								
								echo'<h3 style="text-align:center;"><font color="blue">Cobr jobs = '.$job_count.',  </font> <font color="red"> RI Jobs = 33</font></h3>';
								}
								
								
                            ?>
						</tbody>
					  </table>
					</div>
				  </div>
				  
				   
				  <div class="col-sm-12">
                                        <div class="col-sm-5 radio-cisco">
                                            <input id="radio-3" name="radio" value="component" type="radio" checked>
                                            <label for="radio-3" class="radio-label" style="color:#000;">Proceed with CoBR</label>
                                        </div>
										<!--
                                        <div class="col-sm-5 radio-cisco">
                                            <input id="radio-4" name="radio" value="sanity" type="radio">
                                            <label  for="radio-4" class="radio-label" style="color:#000;">Execute complete sanity</label>
                                        </div>
										-->
                  </div>
				
				  <!-- /page content --> 
				  <div class="col-sm-12" style="text-align:center;">
				  <br>
				<button class="btn btn-primary" type="submit" id="SelectedValues">Submit</button>
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
			<input type="checkbox" value="1%-25%" id="range1" /> &nbsp;1%-25%<br>
			<input type="checkbox" value="25%-50%" id="range2" />&nbsp; 26%-50%<br>
			<input type="checkbox" value="50%-75%" id="range3" />&nbsp; 51%-75%<br>
			<input type="checkbox" value="75%-100%" id="range4" checked /> &nbsp;76%-100%<br>
			<input type="checkbox" value="91%-100%" id="range5" />&nbsp; 91%-100%

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
  
  <script>
		var values = new Array();
		var selectedRowValue = '';
		var selectedTestCases = [];
		var testcases_option = '';
		$("#utimsForm").submit(function(event) {
			event.preventDefault();
			$.each($("input[name='table_records']:checked").parents("td").siblings(".testSuite"), function() {
				values.push($(this).text());
			});
			selectedRowValue = values.toString();
			selectedTestCases[0] = "utims";
			selectedTestCases[1] = selectedRowValue;
			if($("#radio-3").is(':checked')){
				testcases_option = $("#radio-3").val();
			}else if($("#radio-4").is(':checked')){
				testcases_option = $("#radio-4").val();
			}
			selectedTestCases[2] = testcases_option;
			if(selectedTestCases[1] == '' && selectedTestCases[2]== 'component'){
				alert("Please Enter Valid Details");
			}else{
				unique = JSON.stringify(selectedTestCases);
				$.ajax({
					url:'ajaxHandlerBetweenPages.php',
					type:'POST',
					data:{json:unique},
					success:function(data){
						if(data.indexOf('utims')){
							location.href = "success.php";
						}else {
							alert("Call to Cisco Server Failed!");
						}
					},error:function(xhr,desc,err){
						alert("AJAX failed"+ xhr.statusText);
						console.log(err);
					}
				});
			}
		});
	</script>

  </body>
</html>
