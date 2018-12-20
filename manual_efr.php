<?php include "header.php" ?>
<?php
	session_start();
	include("dbconfig.php");
	$from_efr = $_SESSION["from_EFRNumber"];
	$to_efr=$_SESSION["to_EFRNumber"];
	$cfd = $_SESSION["CFD"];
	$newout = $_SESSION["cfdFile"];
	//echo $_SESSION["imgpth"];
	//echo $_SESSION["lineup"];
	//echo $_SESSION["branch"];
	$branch =$_SESSION["efr_branch"];
	echo $branch;
	$_SESSION["COMPONENT"]="";
	$_SESSION["component_array"]="";
	$_SESSION["CHFILES"]="";
	$_SESSION["chfiles_array"]="";
	$_SESSION["Bugids"]="";
	$_SESSION["Bugid_array"]="";
	
	$component=array();
	$chfiles=array();
	$bugids=array();
	print $from_efr ;
	print $to_efr;
	if($from_efr != "" and $to_efr !=""){
		ob_start();
		echo '
			<div id="preloader">
				<div id="status">
					<div class="progress1">
						<p style="text-align:center;">Retrieving Bug Information from EFR Number :: '.$from_efr .'. to :: '.$to_efr .'. </p>
						<div class="circle done">
							<span class="label">1</span>
							<span class="title">  &nbsp; &nbsp;Contacting PIMS</span>
						</div>
						<span class="bar done"></span>
						<div class="circle done">
							<span class="label">2</span>
							<span class="title">  &nbsp; &nbsp;Matching Bug-Ids</span>
						</div>
						<span class="bar half"></span>
						<div class="circle active">
							<span class="label">3</span>
							<span class="title">  &nbsp; &nbsp;Getting Buglist</span>
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
	
	
	
	
	$component=array();
	$chfiles=array();
	$perl_result1 = "";
	
	exec("perl fetch_bugid.pl $from_efr $to_efr &", $perl_result1);
	//print_r($perl_result1);
	$part1=implode(',',$perl_result1);
	#print $part1;
	$pattern1="/\-{4}(.*)\*{4}/";
	if(preg_match($pattern1, $part1, $matches_out))
	{
		print"matching";
		$st=$matches_out[1];
		$_SESSION["Bugids"]=$matches_out[1];
		#print $_SESSION["Bugids"];
		$bugids=(explode(" ",$_SESSION["Bugids"]));
		$_SESSION["Bugid_array"]=$bugids;
		$bugarray=$_SESSION["Bugid_array"];
		#print_r($bugarray);
		
	}
	$pattern1="/\*{4}(.*)\+/";
	if(preg_match($pattern1, $part1, $matches_out))
	{
		$_SESSION["COMPONENT"]=$matches_out[1];
		#print $_SESSION["COMPONENT"];
		$component=(explode(" ",$_SESSION["COMPONENT"]));
		$_SESSION["component_array"]=$component;
		$component_array=$_SESSION["component_array"];
		#print_r ($_SESSION["component_array"]);
		
		
	}
	#print $st;
	$dest_array = explode(',', $st);
	$string1 = implode(',', $dest_array);
	#print_r ($perl_result1);
	$newVariable1 = str_replace(" ", ",", $string1);
	$str1 = implode(',',array_unique(explode(',', $newVariable1)));
	
	if($str1 != ""){
		$_SESSION["bugList"] = $str1;
		ob_start();

		echo '<script src="jquery/dist/jquery.min.js"></script>
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
	
	$descriptorspec = array(
   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
   1 => array("file", "error-output.txt", "a"),  // stdout is a pipe that the child will write to
   2 => array("file", "error-output.txt", "a") // stderr is a file to write to
      );
	
	//print "printingggg";
	
	$perl_result="";
	//$process = (proc_open("perl fddts_mult.pl $db_name NULL NULL NULL $username $str1 NULL &",$descriptorspec,$pipe));
	//exec("perl fddts_mult.pl $db_name NULL NULL NULL $username $str1 NULL &",$perl_result);
	 
	
	#$result= trim($perl_result);
	#$part=implode(',',$perl_result);
	#print $part;
	#$parts = explode('>', $perl_result[sizeof($perl_result)-1]);
	#print("****");
	#print_r($parts);
	#print("****");
	#$component_chfiles= trim($parts[0]);
	#print($component_chfiles);
	#$pattern="/\+{4}\s*(\S+)\-{4}/";
	/*if(preg_match($pattern, $component_chfiles, $matches_out))
	{
		$_SESSION["Bugids"]=$matches_out[1];
		#print $_SESSION["Bugids"];
		$bugids=(explode(",",$_SESSION["Bugids"]));
		$_SESSION["Bugid_array"]=$bugids;
		print("****");
		print_r ($_SESSION["Bugid_array"]);
		print("****");
	}
	$pattern = "/\-{4}\s*(\S+\+{1})/";
    if(preg_match($pattern, $component_chfiles, $matches_out))
	{
		$_SESSION["COMPONENT"]=$matches_out[1];
		#print $_SESSION["COMPONENT"];
		$component=(explode("+",$_SESSION["COMPONENT"]));
		$_SESSION["component_array"]=$component;
		print_r ($_SESSION["component_array"]);
		
	}
	*/
    $bug = explode(",",$str1);
	$mma = implode("','", $bug);
	#print($str1);
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
					<!---
				 <li><a href="jobstatus.php"><i class="fa fa-server icon_nav"></i> Job Status</a></li> -->
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
		   <h2 style="text-align:center;">Bug and component Details</h2>
			   <div class="col-md-12 col-sm-12 col-xs-12" style="float: left">
			   <form action="" id="bugDetails">
                <div class="x_panel_cisco">
				<input type="text" id="myInput" class="light-table-filter" data-table="order-table" placeholder="Search" style="float:right;">
                  <div class="x_content">
                    <div class="table-responsive"><?php 
					
					   /* print "printing....";
						print $process;
						if (is_resource($process)){
						//echo "reached process";
						
						//print_r($pipe[1]);
						
						echo stream_get_contents($pipes[1]);
						while ($s = fgets($pipes[1])) { print $s; }
						} 
					*/
					?>
					
                      <table class="table table-striped jambo_table bulk_action order-table">
                        <thead>
                          <tr class="headings">
                            <th style="text-align:center; class="column-title">Bug-Id List </th>
                            
                             
                          </tr>
                        </thead>
						<tbody>
						
						<?php
								
								
						
								$string_bug = implode(',', $bugarray);	
								$string_comp = implode(',', $component_array);	
							$iter=0;
								foreach ($bugarray as &$value)
									{ 
										echo '<tr class="even pointer">';
									
									echo '<td class=" " style="text-align:center">'.$value.'</td>';
								
									echo '</tr>';
										$iter++;
									}
						
						?>
					</tbody>
                      </table>
					 
					 
						<table class="table table-striped jambo_table bulk_action order-table">
						
                        <thead>
                          <tr class="headings">
              
                            <th style="text-align:center; class="column-title">component List</th>
                            
                            
                          </tr>
                        </thead>
						
                        <tbody>
                        	<?php
								/*$sql="select * from Mapping where Bug_id in('$mma')";
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
								
								mysql_close($conn);*/
								
								#print ($string_bug);
								#print($string_comp);
								
								$iter=0;
								foreach ($component_array as &$value)
									{ 
										echo '<tr class="even pointer">';
									
									echo '<td class=" " style="text-align:center">'.$value.'</td>';
									echo '</tr>';
										$iter++;
									}

								
									
									
								
							?>
                        </tbody>
                      </table>
					 
                    </div>


                  </div>
				  <div class="col-sm-12" style="text-align:center;">
				<button class="btn btn-primary" type="submit">Proceed</button>
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
				unique = JSON.stringify(selectedTestCases);
				$.ajax({
					url:'ajaxHandlerBetweenPages.php',
					type:'POST',
					data:{json:unique},
					success:function(data){
						if(data.indexOf('bugdetails')){
							location.href = "map_re.php";
						}else {
							alert("Call to Cisco Server Failed!");
						}
					},error:function(xhr,desc,err){
						alert("AJAX failed"+ xhr.statusText);
						console.log(err);
					}
				});
			
		});
	</script>
	<script>
    $(window).on("load", function() {
        $("#status").fadeOut();
        $("#preloader").delay(0).fadeOut("slow"); // will fade out the white DIV that covers the website.
        $("body").delay(0).css({"overflow":"visible"});
    });

    </script>
	<script src="js/custom.js"></script>
  </body>
</html>
