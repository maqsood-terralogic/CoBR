<?php
    session_start();
    include("dbconfig.php");
	$cfd = $_SESSION["CFD"];
	//echo $cfd;
	$chfiles=$_SESSION["chfiles_array"];
	//print_r ($chfiles);
	$info=$_SESSION["Bugid_array"];
    $bugs=$_SESSION["Bugid_array"];
    $bug = explode(",",$bugs);
	//print_r ($bug);
    $mma = implode("','", $bug);
    $typecheck = $_SESSION["rangeQuery"];
    //echo $typecheck;
    //echo $mma."<br>";
    //$typecheck=$_POST['typecheck'];
    //$username=$_SESSION["username"];
	//$branch=$_POST["branch"];
	$testSuite=$_SESSION["impactedfiles"];
	//var_dump($testSuite);
	//echo $testSuite;
	#echo "####";
	//$productName=$_POST['ostype'];
	//$sql="INSERT INTO jobId(chfiles,bugsDB,platform,branch,start,end,username,ostype,jobID) VALUES ('$testSuite','$bugs','$platform','$branch','$sDate','$eDate','$username','$productName','$jobID' )";
	//$retval = mysql_query($sql, $conn);
	/* $sql="select flag from jobId where jobID='$jobID'";
	$retval = mysql_query($sql, $conn );
	$flag= mysql_fetch_array($retval, MYSQL_ASSOC);
	$flag= $flag['flag']; */
    
	
	$component_array=$_SESSION["component_array"];
    $branch_selected=$_SESSION["branch"];
	$branch=$_SESSION["efr_branch"];
	print $branch;
	//echo $branch_selected;
	/*foreach ($component_array as &$value)
	{
		
		exec("perl prrq.pl irs $value $branch_selected &", $perl_result);
		$parts = explode('>', $perl_result[sizeof($perl_result) - 1]);
		$testcases= trim($parts[0]);
		echo '<tr class="even pointer">';
		echo '<td class="a-center ">';
		echo '<input type="checkbox" class="flat checkbox" name="table_records">';
		echo '</td>';
		echo '<td class=" "><a target="blank" href=" http://cdets.cisco.com/apps/dumpcr?content=summary&format=html&identifier='.$row['Bug_Id'].'">'.$cfd.'</a></td>';
		echo '<td class="testcase">'.$_SESSION["COMPONENT"].'</td>';
		echo '<td class=" ">'.$testcases.'</td>';
		foreach ($chfiles as &$ch)
			{
				echo '<td class=" ">'.$ch.'</td>'; 
			}
			echo '</tr>';
											
	}*/
									
	$testSuites = str_replace(",",":,", $testSuite);
    $sql="DELETE FROM mail WHERE username = '$username'";
    $retval = mysql_query($sql, $conn );

    $sql="INSERT INTO mail(username,branch,filesimpacted,bugList) VALUES ('$username','$branch','$testSuites','$bugs')";
    $retval = mysql_query($sql, $conn );
    $files = "";
	$testSuites = $testSuite;
    foreach(explode(":,",$testSuites) as $testSuite){
        $testSuite=trim($testSuite,":,");
		#echo $testSuite;
		#echo "******";
        $forMappingSubstitution = $testSuite;
		//echo $forMappingSubstitution;
		#echo ".....";
        $testSuite = explode(",",$testSuite);
        $testSuite = implode("','",$testSuite);
        //echo "Test Suite - ".$testSuite;
        //echo "For Mapping Substitution - ".$forMappingSubstitution;
        $sql = "SELECT Mapping.Bug_Id,FileName FROM Mapping,code_coverage where (Mapping.chfiles like '$forMappingSubstitution' ) and FileName in ('$testSuite') group by FileName;";
        //echo $sql;
        $retval = mysql_query($sql, $conn);
        $num_rows = mysql_num_rows($retval);

        while($row = mysql_fetch_array($retval, MYSQL_ASSOC)){
			//echo "Entered";
            $files .= $row['FileName']." ";
			//echo $files;
        }
        $files .= "<br>";
    }
    $testSuites = $files;

    //Map.php Content Starts

    $testSuites=str_replace("<br>",":,", $testSuites);
    //echo $testSuites;
    $testSuites=rtrim($testSuites,":,");

    $info=$bugs;
    $uni = implode(',',array_unique(explode(',', $info)));
    $bugids = preg_replace(array('"<a (.*?)>"', '"</a>"'), array('',''), $uni);

    $bug = explode(",",$bugids);
    $mma = implode("','", $bug);

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
                 <button class="btn btn-primary" type="submit"> <i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;Back</button>
               </div>
               <div class="form-group col-sm-6">
                   <h2 for="inputInlineRemember">Select Files</h2>
               </div>
             </form>
           </div>
			   <div class="col-md-12 col-sm-12 col-xs-12">

				 <form action="" id="mapFiles">
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
                             <th class="column-title" width="15%">Committed Bug </th>
                            <th class="column-title" width="15%">Components </th>
                            <th class="column-title">Testcases </th>
                            <th class="column-title" width="15%">Filename </th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i=1;
                                $j=0;$data=0;
								/*if(!empty($testSuites)){
									foreach(explode(":,",$testSuites) as $testSuite){
										//echo "TestSuite - ".$testSuite;
										$forMappingSubstitution = explode(" ",$testSuite);
										$forMappingSubstitution = implode("','",$forMappingSubstitution);
										$testSuite=trim($testSuite);
										$forMappingSubstitution = trim($forMappingSubstitution, ",''"); */
										//echo $forMappingSubstitution;
										//echo "Mapping Substitution - ".$forMappingSubstitution;
										//$testSuite=trim($testSuite,":,");
										#echo ":, gone $testSuite<br>";
										#echo ", seprated $testsuite<br>";
										#echo $typecheck;
										$typecheck = trim($typecheck);
										#sql with code coverage
										//$sql="SELECT Mapping.Bug_Id,code_coverage.Tims_id,code_coverage.Coverage,code_coverage.FileName,code_coverage.functionInFile FROM Mapping,code_coverage WHERE (Mapping.chfiles like '%$testSuite%' AND Mapping.Bug_Id in ('$mma')) AND  (code_coverage.FileName in ('$forMappingSubstitution') $typecheck) order by Mapping.Bug_Id desc";
										#echo $sql;
										#sql without code coverage
										//$sql="SELECT Mapping.Bug_Id,code_coverage.Tims_id,code_coverage.Coverage,code_coverage.FileName,code_coverage.functionInFile FROM Mapping,code_coverage WHERE (Mapping.chfiles like '%$testSuite%' AND Mapping.Bug_Id in ('$mma')) AND  (code_coverage.FileName in ('$forMappingSubstitution')) order by Mapping.Bug_Id desc";
										//echo $sql;
										//$retval = mysql_query($sql, $conn);
										//if($retval){
											//$num_rows = mysql_num_rows($retval);
											//while($row = mysql_fetch_array($retval, MYSQL_ASSOC)){
												/*$data++;
												echo '<tr class="even pointer">';
												echo '<td class="a-center ">';
												echo '<input type="checkbox" class="flat checkbox" name="table_records">';
												echo '</td>';
												echo '<td class=" "><a target="blank" href=" http://cdets.cisco.com/apps/dumpcr?content=summary&format=html&identifier='.$row['Bug_Id'].'">'.$row["Bug_Id"].'</a></td>';
												echo '<td class="testcase">'.$row["Tims_id"].'</td>';
												echo '<td class=" ">'.$row["FileName"].'</td>';
												echo '<td class=" ">'.$row["functionInFile"].'</td>';
												echo '</tr>';
												$i++; */
									$iterator=0;
									
									
									exec("perl prrq_all.pl irs $branch &", $perl_result);
									$parts = explode('>', $perl_result[sizeof($perl_result) - 1]);
									if ($parts[0])
									{
										$_SESSION["complete_sanity_testcases"]= trim($parts[0]);
										#echo $_SESSION["complete_sanity_testcases"];
									}
									
									
									
									
									
									foreach ($component_array as &$value)
									{
										$testcases="No test cases for the component present";
										#echo $value;
										#echo "***";
										echo '<tr class="even pointer">';
										exec("perl prrq.pl irs $value $branch &", $perl_result);
										$parts = explode('>', $perl_result[sizeof($perl_result) - 1]);
										#echo $parts[0];
										if ($parts[0])
										{
										$testcases= trim($parts[0]);
										echo '<td class="a-center " width="8%">';
										echo '<input type="checkbox" class="flat checkbox" name="table_records">';
										echo '</td>';
										}
										else
										{
										echo '<td class="a-center " width="8%">';
										echo '</td>';
										}
										echo '<td class=" " width="15%"><a target="blank" href=" http://cdets.cisco.com/apps/dumpcr?content=summary&format=html&identifier='.$row['Bug_Id'].'">'.$bugs[$iterator].'</a></td>';
										echo '<td class=" " width="15%">'.$value.'</td>';
										echo '<td class="testcase">'.$testcases.'</td>';
										echo '<td class=" " width="15%">'.$chfiles[$iterator].'</td>';
										echo '</tr>';
										$iterator+=1;
											
									}
												
												
											//}
										//}
										$j++;
									//}									
								//}else{
									//echo '<tr class="even pointer">';
									//echo '<td class=" " style="text-align:center;">No data present for the impacted files.</td>';
									//echo '</tr>';
								//}

                                #if($data<1){
                                    #echo '<tr>
                                    #<td></td>
                                    #</tr>';
                                #}
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
    <script src="js/custom.js"></script>
    <script>
		var values = new Array();
		var selectedRowValue = '';
		var selectedTestCases = [];
		$("#mapFiles").submit(function(event) {
			event.preventDefault();
			$.each($("input[name='table_records']:checked").parents("td").siblings(".testcase"), function() {
				values.push($(this).text());
			});
			selectedRowValue = values.toString();
			selectedTestCases[0] = "coveragePage";
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
						if(data.indexOf('coveragePage')){
							location.href = "utims.php";
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