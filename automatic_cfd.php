<?php
	session_start();
	include("dbconfig.php");
	$cfd = $_SESSION["CFD"];
	$newout = $_SESSION["cfdFile"];
	$extra = $_SESSION["rangeQuery"];
	//echo $_SESSION["branch"];
	
     $_SESSION["COMPONENT"]="";
	$_SESSION["component_array"]=array();
	$_SESSION["CHFILES"]="";
	$_SESSION["chfiles_array"]=array();
	$branch_selected=$_SESSION["branch"];
	 
	$component=array();
	$chfiles=array();
	$bug = (explode(",",$cfd));
	if(!empty($newout)){
		$list_array = (explode(",",$newout));
		$bug = array_merge($bug,$list_array);
	}
	$str1 = implode(",",$bug);
	//echo $diff;
	exec("perl fddts.pl $db_name NULL NULL NULL $username $str1 NULL &");
    exec("perl fddts_bck.pl $db_name NULL NULL NULL $username $str1 NULL &", $perl_result);
    $parts = explode('$', $perl_result[sizeof($perl_result) - 1]);
    $files= trim($parts[1]);
	//echo $files;
	$component_chfiles= trim($parts[1]);
	//echo $component_chfiles;
	$pattern = "/(\S+(\S+))\-{4}/";
    if(preg_match($pattern, $component_chfiles, $matches_out))
	{
		$_SESSION["COMPONENT"]=$matches_out[1];
		//print $_SESSION["COMPONENT"];
		$component=(explode(",",$_SESSION["COMPONENT"]));
		$_SESSION["component_array"]=$component;
		#print_r ($_SESSION["component_array"]);
	}
	$pattern="/\-{4}\s*(\S+)\+/";
	if(preg_match($pattern, $component_chfiles, $matches_out))
	{
		$_SESSION["CHFILES"]=$matches_out[1];
		//print $_SESSION["CHFILES"];
		$chfiles=(explode("+",$_SESSION["CHFILES"]));
		$_SESSION["chfiles_array"]=$chfiles;
		#print_r ($_SESSION["chfiles_array"]);
	}

	//var_dump($files);
	include("header.php");
?>

<!-- HTML Content -->

	  <div class="main_container">
		<div class="col-md-3 left_col">
		  <div class="left_col scroll-view" style="margin-top: 85px;">
			<div class="clearfix"></div>
			<br />
			<!-- sidebar menu -->
			<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
			  <div class="menu_section">
				<ul class="nav side-menu">

				 <li><a href="jobstatus.php"><i class="fa fa-server icon_nav"></i> Job Status</a></li>
				 <li><a><i class="fa fa-question-circle icon_nav"></i>  FAQ's</a></li>
				 <li><a><i class="fa fa-id-card-o icon_nav"></i> Contact</a></li>
				</ul>
			  </div>

			</div>
			<!-- /sidebar menu -->


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
				   <h2 for="inputInlineRemember">Select Test Cases</h2>
			   </div>
			 </form>
		   </div>
			   <div class="col-md-12 col-sm-12 col-xs-12">

				 <form action="" id="automaticcfdForm">
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
							 <th class="column-title">Test Cases </th>
							<th class="column-title">Test Beds </th>
							<th class="column-title">Component </th>
							<th class="column-title">FileName </th>
						  </tr>
						</thead>

						<tbody>
							<?php
								/*$sql="select distinct(Tims_id),functionInFile,Coverage,FileName from irs.code_coverage where find_in_set(FileName,'$files')";
								//echo $sql;
								$i =1;
								$retval = mysql_query( $sql, $conn );
								$num_rows = mysql_num_rows($retval);
								while($row = mysql_fetch_array($retval, MYSQL_ASSOC)){ 
									echo '<tr class="even pointer">';
									echo '<td class="a-center">
										<input type="checkbox" class="flat checkbox" name="table_records">
										</td>';
									echo '<td class="testSuite">'.$row["Tims_id"].'</td>';
									echo '<td class=" ">tb1_tb2, tb3_tb4, tb5_tb6, tb7_tb8, tb9_tb10, tb11_tb12, tb14_tb15</td>';
									echo '<td class=" ">'.$row["FileName"].'</td>';
									echo '<td class=" ">'.$row["functionInFile"].'</td>';
									echo '</tr>';
								} */
								
								
								exec("perl prrq_all.pl irs $branch_selected &", $perl_result);
								$parts = explode('>', $perl_result[sizeof($perl_result) - 1]);
								if ($parts[0])
								{
									$_SESSION["complete_sanity_testcases"]= trim($parts[0]);
									#echo $_SESSION["complete_sanity_testcases"];
								}
								
								
								
								
								$iterator=0;
									//print_r($_SESSION[component_array]);
									foreach ($_SESSION["component_array"] as &$value)
									{
										$testcases="No test cases for the component present";
										//echo $value;
										//echo "***";
										echo '<tr class="even pointer">';
										exec("perl prrq.pl irs $value $branch_selected &", $perl_result);
										$parts = explode('>', $perl_result[sizeof($perl_result) - 1]);
										//echo $parts[0];
										if ($parts[0])
										{
											$testcases= trim($parts[0]);
										
											$unique_testcases = array_unique(explode(',', $testcases));
										
											foreach ($unique_testcases as $each_testcase)
											{
												echo '<td class="a-center " width="8%">';
											echo '<input type="checkbox" class="flat checkbox" name="table_records">';
											echo '</td>';
											echo '</td>';
											echo '<td class="testSuite">'.$each_testcase.'</td>';
											echo '<td class=" ">tb1_tb2, tb3_tb4, tb5_tb6, tb7_tb8, tb9_tb10, tb11_tb12, tb14_tb15</td>';
											echo '<td class=" ">'.$value.'</td>';
											echo '<td class=" ">'.$chfiles[$iterator].'</td>';
											echo '</tr>';
											}
											$iterator+=1;
										}
										else
										{
										echo '<td class="a-center ">';
										echo '</td>';
										echo '<td class="testSuite">'.$testcases.'</td>';
										echo '<td class=" ">tb1_tb2, tb3_tb4, tb5_tb6, tb7_tb8, tb9_tb10, tb11_tb12, tb14_tb15</td>';
										echo '<td class=" ">'.$value.'</td>';
										echo '<td class=" ">'.$chfiles[$iterator].'</td>';
										echo '</tr>';
										$iterator+=1;
										}
											
									}
												
								//if($num_rows == 0){

								//}

								mysql_close($conn);
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
                                        <div class="col-sm-5 radio-cisco">
                                            <input id="radio-4" name="radio" value="sanity" type="radio">
                                            <label  for="radio-4" class="radio-label" style="color:#000;">Execute complete sanity</label>
                                        </div>
                  </div> 
				  
				 <div class="col-sm-12" style="text-align:center;">
				 <br>
				<label><!--Get the TC Script Mappings for the Source Code :--></label>  <button class="btn btn-primary" type="submit" >Submit</button>
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
			<input type="checkbox" value="1%-25%" id="range1"/> &nbsp;1%-25%<br>
			<input type="checkbox" value="25%-50%" id="range2"/>&nbsp; 26%-50%<br>
			<input type="checkbox" value="50%-75%" id="range3"/>&nbsp; 51%-75%<br>
			<input type="checkbox" value="75%-100%" id="range4" checked /> &nbsp;76%-100%<br>
			<input type="checkbox" value="91%-100%" id="range5"/>&nbsp; 91%-100%

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
		$("#automaticcfdForm").submit(function(event) {
			event.preventDefault();
			$.each($("input[name='table_records']:checked").parents("td").siblings(".testSuite"), function() {
				values.push($(this).text());
			});
			selectedRowValue = values.toString();
			selectedTestCases[0] = "utimsautomatic";
			selectedTestCases[1] = selectedRowValue;
			console.log(selectedRowValue);
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
						if(data.indexOf('utimsautomatic')){
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
