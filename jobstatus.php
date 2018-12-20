<?php
    session_start();
    include("dbconfig.php");
    $username = $_SESSION['username'];
    $EFRNumber = $_SESSION['EFRNumber'];
    $testCases = $_SESSION["ranTestCases"];
    $bugList = $_SESSION["bugids"];
	$component=$_SESSION["COMPONENT"];
	//echo $component;
	
    if(!$EFRNumber){
        $EFRNumber = "NA";
    }
    $lineup = "NULL";
    $imgpth = "NULL";
    //print_r($testCases);
    $platform="NULL";
    #$platform=$_POST['platform'];
    #exec("perl jobSchedule.pl $username ");
    $lines = file('log.log');
    $regex ="/RequestId = [0-9]{9}/";

    if($lines){
    	foreach ($lines as $line) {
    		if (preg_match($regex, $line,$matched_out)){
    			break;
    		}
    	}
    	$out = implode(',', $matched_out);
    	$id =explode(" ",$out);

        $sql = sprintf("INSERT INTO earmsJobID (jobID,Image,component,efrNumber, bugList, testCases) VALUES ('%s','%s','%s', '%s','%s','%s')",    mysql_real_escape_string($id[2],$conn),
    	mysql_real_escape_string($imgpth,$conn),
    	mysql_real_escape_string($component,$conn),
        mysql_real_escape_string($EFRNumber,$conn),
        mysql_real_escape_string($bugList,$conn),
        mysql_real_escape_string($testCases,$conn));

    	//$sql =  "INSERT INTO earmsJobID (jobID, efrNumber, bugList, testCases) VALUES ('".$id[2]."','".$EFRNumber."','".$bugList."','".$testCases."')";
    	$retval = mysql_query( $sql, $conn );
    	if(! $retval ){
    		die('Could not enter data: ' . mysql_error());
    	}
    	$handle = fopen("log.log", "w+");
    	fclose($handle);
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
                   <h2 for="inputInlineRemember">Job Execution Status</h2>
               </div>
             </form>
           </div>
			   <div class="col-md-12 col-sm-12 col-xs-12">
			   <form action="">
                <div class="x_panel_cisco">
				<input type="text" id="myInput" class="light-table-filter" data-table="order-table" placeholder="Search" style="float:left;">
                  <div class="x_content">
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action order-table">
                        <thead>
                          <tr class="headings">
                            <th class="column-title" width="12%">Job Id </th>
                            <th class="column-title" width="15%">Component </th>
                            <th class="column-title" width="15%">Bug List </th>
                            <th class="column-title">Test Cases </th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql="select * from earmsJobID order by jobID DESC";
                                $retval = mysql_query( $sql, $conn );
                                $row=mysql_num_rows($retval);
                                if($row==0){
                                    echo '<script>
                                        document.getElementById("bug_summary").style.display="none";
                                        </script>';
                                }
								
                                while($row = mysql_fetch_array($retval, MYSQL_ASSOC)) {
                                    echo '<tr class="even pointer">';
                                    echo '<td width="12%"><a href="http://earms-app-2-vm.cisco.com/test/results.jsp?groupname=Scapa&reqid='.$row['jobID'] .'" target="_blank">'.$row['jobID'] .'</a></td>';
									
									echo '<td width="15%">'.$row['component'] .'</td>';
                                    echo '<td width="15%">'.$row['bugList'] .'</a></td>';
                                    echo '<td>'.$row['testCases'] .'</a></td>';
                                    echo '</tr>';
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
			<input type="checkbox" value="1%-25%" id="range1"/> &nbsp;1%-25%<br>
			<input type="checkbox" value="25%-50%" id="range2"/>&nbsp; 26%-50%<br>
			<input type="checkbox" value="50%-75%" id="range3"/>&nbsp; 51%-75%<br>
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

  </body>
</html>
