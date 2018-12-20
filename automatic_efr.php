<?php
    session_start();
    include("dbconfig.php");
?>
<?php include "header.php" ?>
<?php

    $extra = $_SESSION["rangeQuery"];
	$EFRNumber = $_SESSION["EFRNumber"];

	$efr = $EFRNumber;
	//echo $efr;
	if($efr != ""){
		ob_start();
		echo '
			<div id="preloader">
				<div id="status">
					<div class="progress1">
						<p style="text-align:center;font-size: 16px;">Retrieving Bug Information from EFR Number :: '.$EFRNumber .'.</p>
						<div class="circle done">
							<span class="label">1</span>
							<span class="title">  &nbsp;&nbsp;Contacting PIMS</span>
						</div>
						<span class="bar done"></span>
						<div class="circle done">
							<span class="label">2</span>
							<span class="title">  &nbsp;&nbsp;Collecting PCR Numbers</span>
						</div>
						<span class="bar half"></span>
						<div class="circle active">
							<span class="label">3</span>
							<span class="title">  &nbsp;&nbsp;Getting Buglist</span>
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
	}
	$perl_result = "";
	exec("perl fetch_efr.pl $efr &", $perl_result);
	$string = implode(',', $perl_result);
	$newVariable = str_replace(" ", ",", $string);
	$str = implode(',',array_unique(explode(',', $newVariable)));
	//echo $str;
	if($str != ""){
		ob_start();
		echo '	<script src="jquery/dist/jquery.min.js"></script>
        <script>
			var i=2;
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
			</script>';
		ob_end_flush();
		ob_flush();
		flush();
	}
	$perl_result1 = "";
	exec("perl fetch_pcr.pl $str &", $perl_result1);
	$string1 = implode(',', $perl_result1);
	$newVariable1 = str_replace(" ", ",", $string1);
	$str1 = implode(',',array_unique(explode(',', $newVariable1)));
	if($str1 != ""){
		$_SESSION["bugList"] = $str1;
		ob_start();
		echo '	<script src="jquery/dist/jquery.min.js"></script>
        <script>
				var i=3;
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
			</script>';
		ob_end_flush();
		ob_flush();
		flush();
	}

    //var_dump($str1);
    //exec("perl fddts.pl $db_name NULL NULL NULL $username $str1 $newout &");
    exec("perl fddts.pl $db_name NULL NULL NULL $username $str1 NULL &");
    exec("perl fddts_bck.pl $db_name NULL NULL NULL $username $str1 NULL &", $perl_result);
    $parts = explode('$', $perl_result[sizeof($perl_result) - 1]);
    $files= trim($parts[1]);
    //echo $files;
	//$_SESSION["testCases"]=$files;

?>

<!-- HTML Content -->

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


		  <section style="padding-top: 130px;">
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

				 <form action="" id="automaticefrForm">
				<div class="x_panel_cisco">
				<input type="text" id="myInput" class="light-table-filter" data-table="order-table" placeholder="Search" style="float:left;margin-left: 6px">
				  <div class="x_content">
					<div class="table-responsive">
					  <table class="table table-striped jambo_table bulk_action order-table">
						<thead>
						  <tr class="headings">
							<th class="col-xs-1">
							  <input type="checkbox" id="check-all" class="flat"> Select
							</th>
							 <th class="column-title col-xs-2">Test Cases </th>
							<th class="column-title col-xs-2">Test Beds </th>
							<th class="column-title col-xs-2">File Name </th>
							<th class="column-title col-xs-1">Coverage </th>
							<th class="column-title col-xs-3">Functions </th>
						  </tr>
						</thead>

						<tbody>
                            <?php

								$sql="select distinct(Tims_id),functionInFile,Coverage,FileName from irs.code_coverage where find_in_set(FileName,'$files') $extra";
								//echo $sql;
								$i =1;
								$retval = mysql_query( $sql, $conn );
								$num_rows = mysql_num_rows($retval);
								while($row = mysql_fetch_array($retval, MYSQL_ASSOC)){
									echo '<tr class="even pointer">';
									echo '<td class="a-center col-xs-1" style="word-wrap: break-word;">
										<input type="checkbox" class="flat checkbox"  name="table_records">
										</td>';
									echo '<td class="testSuite col-xs-2" style="word-wrap: break-word;">'.$row["Tims_id"].'</td>';
									echo '<td class="col-xs-2" style="word-wrap: break-word;">tb1_tb2, tb3_tb4, tb5_tb6, tb7_tb8, tb9_tb10, tb11_tb12, tb14_tb15</td>';
									echo '<td class="col-xs-2 " style="word-wrap: break-word;">'.$row["FileName"].'</td>';
									echo '<td class="col-xs-1 " style="word-wrap: break-word;">'.$row["Coverage"].'</td>';
									echo '<td class="col-xs-3 " style="word-wrap: break-word;">'.$row["functionInFile"].'</td>';
									echo '</tr>';
								}

								if($num_rows == 0){

								}

								mysql_close($conn);
                            ?>
						</tbody>
					  </table>
					</div>
				  </div>
				 <div class="col-sm-12" style="text-align:center;">
				<label>Get the TC Script Mappings for the Source Code :</label>  <button class="btn btn-primary" type="submit" >Next</button>
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
    <script>
    $(window).on("load", function() {
        $("#status").fadeOut();
        $("#preloader").delay(0).fadeOut("slow"); // will fade out the white DIV that covers the website.
        $("body").delay(0).css({"overflow":"visible"});
    });

    </script>
	<script src="js/custom.js"></script>
    <script>
		var values = new Array();
		var selectedRowValue = '';
		var selectedTestCases = [];
		$("#automaticefrForm").submit(function(event) {
			event.preventDefault();
			$.each($("input[name='table_records']:checked").parents("td").siblings(".testSuite"), function() {
				values.push($(this).text());
			});
			selectedRowValue = values.toString();
			selectedTestCases[0] = "utimsautomatic";
			selectedTestCases[1] = selectedRowValue;
			if(selectedTestCases[1] == ''){
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
?>
