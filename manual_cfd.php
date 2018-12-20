<?php
	include("dbconfig.php");
	session_start();

	$cfd = $_SESSION["CFD"];
	$newout = $_SESSION["cfdFile"];
	//echo $_SESSION["imgpth"];
	//echo $_SESSION["lineup"];
	//echo $_SESSION["branch"];
	
	$_SESSION["COMPONENT"]="";
	$_SESSION["component_array"]="";
	$_SESSION["CHFILES"]="";
	$_SESSION["chfiles_array"]="";
	
	$component=array();
	$chfiles=array();
	
	$mma = explode(',',$cfd);
	$mma = implode("','",$mma);
	//echo $diff;
	exec("perl fddts.pl $db_name NULL NULL NULL $username $cfd NULL &", $perl_result);
	#exec("perl fddts_bck.pl $db_name NULL NULL NULL $username $diff $newout &", $perl_result);
	//print_r ($perl_result);
	$parts = explode('>', $perl_result[sizeof($perl_result) - 1]);
	$component_chfiles= trim($parts[0]);
	//print($component_chfiles);
	$pattern = "/[$]\s+(\S+)\-{4}/";
    if(preg_match($pattern, $component_chfiles, $matches_out))
	{
		$_SESSION["COMPONENT"]=$matches_out[1];
		#print $_SESSION["COMPONENT"];
		$component=(explode(",",$_SESSION["COMPONENT"]));
		$_SESSION["component_array"]=$component;
		print_r ($_SESSION["component_array"]);
	}
	$pattern="/\-{4}\s*(\S+)\+/";
	if(preg_match($pattern, $component_chfiles, $matches_out))
	{
		$_SESSION["CHFILES"]=$matches_out[1];
		#print $_SESSION["CHFILES"];
		$chfiles=(explode("+",$_SESSION["CHFILES"]));
		$_SESSION["chfiles_array"]=$chfiles;
		#print_r ($_SESSION["chfiles_array"]);
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
		   <h2 style="text-align:center;">Bug Details</h2>
			   <div class="col-md-12 col-sm-12 col-xs-12">
			   <form action="" id="bugDetails">
				<div class="x_panel_cisco">
				<input type="text" id="myInput" class="light-table-filter" data-table="order-table" placeholder="Search" style="float:right;">
				  <div class="x_content">
					<div class="table-responsive">
					  <table class="table table-striped jambo_table bulk_action order-table">
						<thead>
						  <tr class="headings">
							<th>
							  <input type="checkbox" id="check-all" class="flat"> Select
							</th>
							<th class="column-title">Bug-Id </th>
							<th class="column-title">Dev-Engineer </th>
							<th class="column-title">Product </th>
							<th class="column-title">Component </th>
							<th class="column-title">Headlines </th>
						  </tr>
						</thead>

						<tbody>
							<?php
								echo $mma;
								$sql="select * from Mapping where Bug_id in('$mma')";
								$retval = mysql_query( $sql, $conn );
								$num_rows = mysql_num_rows($retval);
								while($row = mysql_fetch_array($retval, MYSQL_ASSOC)){
									echo '<tr class="even pointer">';
									echo '<td class="a-center">
										<input type="checkbox" class="flat checkbox" name="table_records">
										</td>';
									echo '<td class="bugid">'.$row["Bug_Id"].'</td>';
									echo '<td class=" ">'.$row["Dev_Enginner"].'</td>';
									echo '<td class=" ">'.$row["Product"].'</td>';
									echo '<td class=" ">'.$row["Component"].'</td>';
									echo '<td class=" ">'.$row["Headings"].'</td>';
									echo '</tr>';
								}

								mysql_close($conn);
							?>
						</tbody>
					  </table>
					</div>


				  </div>
				  <div class="col-sm-12" style="text-align:center;">
				<button class="btn btn-primary" type="submit">Submit</button>
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
		<button type="button" class="btn btn-default" data-dismiss="modal">Save</button>
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
		$("#bugDetails").submit(function(event) {
			event.preventDefault();
			$.each($("input[name='table_records']:checked").parents("td").siblings(".bugid"), function() {
				values.push($(this).text());
			});
			selectedRowValue = values.toString();
			selectedTestCases[0] = "bugdetails";
			selectedTestCases[1] = selectedRowValue;
			console.log(selectedRowValue);
			if(selectedTestCases[1] == ''){
				alert("Please Enter Valid Details");
			}else{
				unique = JSON.stringify(selectedTestCases);
				$.ajax({
					url:'ajaxHandlerBetweenPages.php',
					type:'POST',
					data:{json:unique},
					success:function(data){
						if(data.indexOf('bugdetails')){
							location.href = "impactedsource.php";
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
