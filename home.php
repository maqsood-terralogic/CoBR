<?php session_start(); ?>
<style>
.home-btn-cisco{
	display:none;
}
</style>
<?php include "header.php" ?>
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view" style="margin-top: 135px;">
                    <div class="clearfix"></div>
                    <br />
                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <h3>Choose Product</h3>
                            <ul class="nav side-menu">
                                <li>
                                    <div class="radio-cisco">
                                        <input id="radio-1" name="radio" type="radio">
                                        <label for="radio-1" class="radio-label">NCS4K</label>
                                    </div>
                                </li>
                                <li>
                                    <div class="radio-cisco">
                                        <input id="radio-2" name="radio" type="radio">
                                        <label  for="radio-2" class="radio-label">N9K</label>
                                    </div>
                                </li><hr>
								<?php
								/*
                                <li>
                                    <a><i class="fa fa-sitemap"></i> Code Coverage <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a>Regression<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="#">N9K <span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li class="sub_menu"><a href="http://10.106.30.178/N9K/cflow.report" target="_blank" style="text-decoration:underline;">CFLOW</a></li>
                                                            <li class="sub_menu"><a href="http://wwwin-people.cisco.com/chkomma/report_regression_N9K/" target="_blank" style="text-decoration:underline;">LCOV</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
								
                                        <li><a>Sanity<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="#">NCS4K <span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li class="sub_menu"><a href="#">ARWEN Sanity <span class="fa fa-chevron-down"></span></a>
                                                            <ul class="nav child_menu">
                                                                <li class="sub_menu"><a href="http://10.106.30.178/NCS4K/cflow_sanity.txt" target="_blank" style="text-decoration:underline;">CFLOW</a></li>
                                                            </ul>
                                                        </li>

                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
										*/
								?>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- /sidebar menu -->
                </div>
            </div>


            <!-- page content -->
            <div class="right_col" role="main">
                <!-- top tiles
                <div class="row tile_count">
                    <ul class="inner-menu">
                        <li><a href="#" data-toggle="modal" onclick="$('#xe').click()" data-target="#myModal">Config<i></i></a></li>
                        <li><a href="jobstatus.php">Job Status<i></i></a></li> 
						<?php
                        #<li><a href="faq.php">FAQ's<i></i></a></li>
                        #<li><a href="#">Contact<i></i></a></li>
						?>
                    </ul>
                </div> --> 
                <!-- /top tiles -->

                <section style="padding-top: 180px;">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12" id="home-img" style="text-align:center;">
                                <img src="images/123.png" style="width:77%;">
                            </div>
                        </div>

                        <div class="x_panel" id="efrNumberPopup" style="display:none;">
                            <div class="row">
                                <form id="" action="" method="post">
                                    <div class="col-sm-12">
                                        <div class="col-sm-5 radio-cisco">
                                            <input id="radio-3" name="radio" type="radio" onclick="efrNumber();" checked>
                                            <label for="radio-3" class="radio-label" style="color:#000;">EFR Number</label>
                                        </div>
										<!--
                                        <div class="col-sm-5 radio-cisco">
                                            <input id="radio-4" name="radio" onclick="bugLists();" type="radio">
                                            <label  for="radio-4" class="radio-label" style="color:#000;">Bug Lists(CFD/LFD)</label>
                                        </div> -->
                                    </div>
                                </form><br>
                                <span id="buglist-alert-report"></span>
                                <span id="efrNumber-alert-report"></span><br>
                                <div class="col-sm-12">
                                    <div id="efrNumberDiv">
                                        <form id="efrForm" method="post">
									
                                            <div class="col-sm-12">
                                                <label for="fullname">From EFR Number <span style="color:red;">*</span> :</label>
                                                <input autocomplete="off" type="text" id="FromEFRNumber" class="form-control" name="FromEFRNumber"  placeholder="EFR Number"/><br>
                                            </div>
											<div class="col-sm-12">
                                                <label for="fullname">To EFR Number <span style="color:red;">*</span> :</label>
                                                <input autocomplete="off" type="text" id="ToEFRNumber" class="form-control" name="ToEFRNumber"  placeholder="EFR Number"/><br>
                                            </div>
											<!--Model popup
                                            <div class="col-sm-12">
												<label for="fullname">Image Path :</label>
                                                <input autocomplete="off" type="text" id="efrimgpth" class="form-control" name="efrimgpth"  placeholder="Image Path"/><br>
                                            </div>
                                            <div class="col-sm-12">
                                                <label for="lineup">Line Up <span style="color:red;">*</span> :</label>
                                                <select id="efrlineup" name="efrlineup" class="form-control">
                                                    <option value="">--select--</option>
                                                    <option value="xr-dev">xr-dev</option>
                                                    <option value="6112">6112</option>
                                                </select><br>
                                            </div>
											-->
                                            <div class="col-sm-4">
                                                <label for="branch">RI Selection <span style="color:red;">*</span> :</label>
                                                <select id="efrbranchName" name="efrbranchName" class="form-control">
                                                    <option value="">--select--</option>
                                                    <option value="Arwen">SC_Packet</option>
													<option value="MC">MC_Packet</option>
													<option value="OTN">SC_OTN</option>
													
                                                </select><br>
                                            </div>
											
                                            <div class="col-sm-12" style="text-align: center;">
                                                <button class="btn btn-primary" type="submit" >Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
								
                                <div class="col-sm-12">
                                    <div id="bugListsDiv" style="display:none;">
                                        <form id="bugListsForm" action="" method="post" enctype="multipart/form-data">
											<div class="col-sm-12">
                                            <label for="fullname">Bug Lists <span style="color:red;">*</span> :</label>
                                            <input autocomplete="off" type="text" id="bugLists" class="form-control" name="bugLists"  placeholder="bug Lists" /><br>
											</div>
                                            <div class="col-sm-12">
												<label for="imgpth">Image Path <span style="color:red;">*</span> :</label>
                                                <input autocomplete="off" type="text" id="imgpth" class="form-control" name="imgpth"  placeholder="Image Path"/><br>
                                            </div>
                                            <div class="col-sm-12">
                                                <label for="lineup">Line Up <span style="color:red;">*</span> :</label>
                                                <select id="lineup" name="lineup" class="form-control">
                                                    <option value="">--select--</option>
                                                    <option value="xr-dev">xr-dev</option>
                                                    <option value="6112">6112</option>
                                                </select><br>
                                            </div>
											<div class="col-sm-12">
                                                <label for="branch">Branch <span style="color:red;">*</span> :</label>
                                                <select id="bugbranchName" name="bugbranchName" class="form-control">
                                                    <option value="">--select--</option>
                                                    <option value="Arwen">Arwen</option>
                                                </select><br>
                                            </div>

                                            <div class="col-sm-12" style="text-align:center;">
                                                <button type="Submit" class="btn btn-primary" >Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
                            <input  class="checkbox-custom" name="checkbox-1"  type="checkbox" name="mode" value="manual" id="manualRadio" required checked/>&nbsp;&nbsp; Manual <i>(Step by Step selection of bugs and impacted components)</i>
                        </label><br><br>
                    

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="modalSave" class="btn btn-primary" data-dismiss="modal">Save</button>
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
    function handleFileSelect(evt) {
        var files = evt.target.files; // FileList object

        // Loop through the FileList
        for (var i = 0, f; f = files[i]; i++) {

            var reader = new FileReader();

            // Closure to capture the file information.
            reader.onload = (function(theFile) {
                return function(e) {
                    // Print the contents of the file
                    var span = document.createElement('span');
                    span.innerHTML = ['<p>',e.target.result,'</p>'].join('');
                    //document.getElementById('list').insertBefore(span, null);
                    textFile = e.target.result;
                };
            })(f);

            // Read in the file
            //reader.readAsDataText(f,UTF-8);
            //reader.readAsDataURL(f);
            reader.readAsText(f);
        }
    }

    document.getElementById('bugListsFile').addEventListener('change', handleFileSelect, false);
    </script>
    <script>

    //Global Variables
    var fileNameForRedirect, coverageRange;
    var mode = "automatic";
    var textFile = '';
    //Home page LR status js
    //var modal = document.getElementById('myModal');
    var btn = document.getElementById("radio-1");
    //var span = document.getElementsByClassName("close")[0];
    btn.onclick = function() {
        document.getElementById("home-img").style.display='none';
        document.getElementById("efrNumberPopup").style.display='block';
    }

    function efrNumber(){
        document.getElementById('efrNumberDiv').style.display ='block';
        document.getElementById('bugListsDiv').style.display ='none';
        document.getElementById('buglist-alert-report').style.display ='none';
        document.getElementById('efrNumber-alert-report').style.display ='block';
    }
    function bugLists(){
        document.getElementById('efrNumberDiv').style.display = 'none';
        document.getElementById('bugListsDiv').style.display ='block';
        document.getElementById('efrNumber-alert-report').style.display ='none';
        document.getElementById('buglist-alert-report').style.display ='block';
    }

    //Configuration Modal
    function getFileName(type){
        var range = [];
        if($("#manualRadio").is(':checked')){
            mode = "manual";
            if(type == "efr"){
                fileNameForRedirect = "fddts_efr.php";
            }else if(type == "buglist"){
                fileNameForRedirect = "fddts.php";
            }
        }else{
            mode = "automatic";
            if(type == "efr"){
                fileNameForRedirect = "utims_efr.php";
            }else if(type == "buglist"){
                fileNameForRedirect = "utims.php";
            }
        }
        //Range Combination for Coverage Percentage
        if($("#range1").is(":checked")){
            range.push('1%-25%');
        }
        if($("#range2").is(":checked")){
            range.push('26%-50%');
        }
        if($("#range3").is(":checked")){
            range.push('51%-75%');
        }
        if($("#range4").is(":checked")){
            range.push('76%-90%');
        }
        if($("#range5").is(":checked")){
            range.push('91%-100%');
        }
        coverageRange = range.toString();
        console.log(coverageRange);
        return fileNameForRedirect;
    }
    
    //AJAX calls for Form Submit
    (function ($) {
        $(document).ready(function() {
            var fileName;

            $("#efrForm").submit(function(event) {
                event.preventDefault();
                var efrData = [];
                efrData[3] = $("input#FromEFRNumber").val();
                efrData[4] = $("input#ToEFRNumber").val();
                efrData[5] = $("select#efrbranchName").val();
                if(efrData[3] !== ''){
                    if(efrData[5] == ''){
                        alert("Kindly select the branch");
                    }else{
                        fileName = getFileName("efr");
                        efrData[0] = mode;
                        efrData[1] = coverageRange;
                        efrData[2] = "EFR";
                        unique = JSON.stringify(efrData);
                        $.ajax({
                            url:'ajaxHandler.php',
                            type:'POST',
                            data:{json:unique},
					
                            success:function(data){
                                if(data.indexOf('true') != -1){
                                    if(data.indexOf('manualefr') != -1){
                                        console.log(data);
                                        location.href = "manual_efr.php";
                                    }else if(data.indexOf('automaticefr') != -1){
                                        location.href = "automatic_efr.php";
                                    }
                                }else {
                                    console.log(data.indexOf("true"));
                                    alert("Call to Cisco Server Failed!");
                                }
                            },error:function(xhr,desc,err){
                                alert("AJAX failed"+ xhr.statusText);
                                console.log(err);
                            }
                        });
                    }
                }
                else{
                    document.getElementById("efrNumber-alert-report").innerHTML="Please Enter Valid Details";
                }


            });

            $("#bugListsForm").submit(function(event) {
                event.preventDefault();
                //alert(textFile);
                var bugListData = [];
                bugListData[3] = $("input#bugLists").val();
                bugListData[4] = textFile;
				bugListData[5] = $("input#imgpth").val();
				bugListData[6] = $("select#lineup").val();
				bugListData[7] = $("select#bugbranchName").val();
                console.log(bugListData[4]);
                if(bugListData[3] == '' || bugListData[5] == '' || bugListData[6] == '' || bugListData[7] == ''){
                    document.getElementById("buglist-alert-report").innerHTML = "Please Enter Valid Details";
                }else{
                    fileName = getFileName("buglist");
                    bugListData[0] = mode;
                    bugListData[1] = coverageRange;
                    bugListData[2] = "CFD";
					bugListData[5] = $("input#imgpth").val();
					bugListData[6] = $("select#lineup").val();
					bugListData[7] = $("select#bugbranchName").val();
                    unique = JSON.stringify(bugListData);
                    $.ajax({
                        url:'ajaxHandler.php',
                        type:'POST',
                        data:{json:unique},
                        success:function(data){
                            if(data.indexOf('true') != -1){
                                if(data.indexOf('manualcfd') != -1){
                                    location.href = "manual_cfd.php";
                                }else if(data.indexOf('automaticcfd') != -1){
                                    location.href = "automatic_cfd.php";
                                }
                                //location.href("");
                                //location.reload();
                                //document.getElementById("").reset();
                            }else {
                                alert("Call to Cisco Server Failed!");
                            }
                        },error:function(xhr,desc,err){
                            alert("AJAX failed"+ xhr.statusText);
                            console.log(err);
                        }
                    });
                }
                //
                //alert(e.target.result);
            });
        });
    })(jQuery);


    </script>
    <script src="js/custom.js"></script>

</body>
</html>
