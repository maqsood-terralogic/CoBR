<?php
    session_start();
    include("dbconfig.php");
	
    $info=$_SESSION["bugids"];
	
	 $bugs=$_SESSION["Bugid_array"];
    //$username = $_SESSION["username"];
    if($username==""){
        header("Location: index.php");
    }
    //$branch=$_POST["branch"];
    //$platform=$_POST['platform'];
    //$typecheck=$_POST['typecheck']; mode value - automatic or manual
    //$db_name=$_POST['ostype'];
    //$jobID=$_POST['jobID'];
	$component_array=$_SESSION["component_array"];
	#print_r($component_array);
	#print_r($bugs);
	$branch=$_SESSION["efr_branch"];
	print $branch;

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
                   <h2 for="inputInlineRemember">Associated Source Code Mapping</h2>
               </div>
             </form>
           </div>
			   <div class="col-md-12 col-sm-12 col-xs-12">
			   <form action="" id="impactedSourceForm">
                <div class="x_panel_cisco">
				<input type="text" id="myInput"  class="light-table-filter" data-table="order-table" placeholder="Search" style="float:left;margin-left: 6px">
                  <div class="x_content">
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action order-table">
                        <thead >
                          <tr class="headings" >
                            <th>
                              <input type="checkbox" id="check-all" class="flat"> Select
                            </th>
                            <th class="column-title">CDETS </th>
							<th class="column-title">Component(s) </th>
                            <th class="column-title">Impacted Source List </th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no_check=0;
                            $no_checker=0;
                            $i=1;
							$iterator=0;
                            foreach(explode(",",$info) as $bugid){
										
                                $sql="select * from Mapping where Bug_Id='$bugid' ";
                                $retval = mysql_query( $sql, $conn );
                                if(! $retval ) {
                                    die('Could not fetch data: ' . mysql_error());
                                }
                                $num_rows = mysql_num_rows($retval);
                                if($num_rows==0)  {
                                    echo '<tr style="text-align:center;>'.PHP_EOL;
                                    echo 'No Bug found </tr>'.PHP_EOL;
                                }
                                else{
                                    while($row = mysql_fetch_array($retval, MYSQL_ASSOC)) {
                                        echo '<tr class="even pointer">';
                                        echo '<td class="a-center ">';
                                        echo '<input type="checkbox" class="flat checkbox" name="table_records" id="'.$i.'">';
                                        echo '</td>';
                                        echo '<td class="bugid"><a target="blank" href="http://cdets.cisco.com/apps/dumpcr?content=summary&format=html&identifier='.$bugs[$iterator].'">'.$bugs[$iterator].'</a></td>';
										
										echo '<td class="component">'.$component_array[$iterator].'</td>';
										
                                        echo '<td class="impactedfiles">';
                                        foreach(explode("\n",$row['chfiles']) as $files){
                                            echo $files.'<br>';
                                            if($files=="No .c/.h files impacted"){
                                                $no_check++;
                                                echo"<script> document.getElementById('$i').disabled = true;</script>";
                                                echo"<script> document.getElementById('$i').checked = false;</script>";
                                            }
                                            $no_checker++;
                                        }
                                        echo '</td>';
                                        //echo '<td style="font-size: 15px;">'.$row['Headings'].'</td>'.PHP_EOL;
                                        echo '</tr>';
                                        $i++;
										$iterator+=1;
                                    }
								}  
                            }
                            mysql_close($conn);
                        ?>
                        </tbody>
                      </table>
                    </div>


                  </div>
				  <div class="col-sm-12" style="text-align:center;">
				<label>Get the TC Script Mappings for the Source Code :</label>  <button class="btn btn-primary" type="submit">Next</button>
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
    <script>
		var values = new Array();
		var valuesFiles = new Array();
		var selectedRowValue = '';
		var selectedRowValueFiles = '';
		var selectedTestCases = [];
		$("#impactedSourceForm").submit(function(event) {
			event.preventDefault();
			$.each($("input[name='table_records']:checked").parents("td").siblings(".bugid"), function() {
				values.push($(this).text());
			});
			selectedRowValue = values.toString();
            $.each($("input[name='table_records']:checked").parents("td").siblings(".impactedfiles"), function() {
				if(($(this).text()).indexOf("No .c/.h files impacted") == -1){
					valuesFiles.push($(this).text()+":,");
				}
			});
			selectedRowValueFiles = valuesFiles.toString();
			selectedTestCases[0] = "impactedSource";
			selectedTestCases[1] = selectedRowValue;
            selectedTestCases[2] = selectedRowValueFiles;
			if(selectedTestCases[1] == ''){
				alert("Please Enter Valid Details");
			}else{
				unique = JSON.stringify(selectedTestCases);
				$.ajax({
					url:'ajaxHandlerBetweenPages.php',
					type:'POST',
					data:{json:unique},
					success:function(data){
						if(data.indexOf('impactedSource')){
							location.href = "map_re.php";
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
