<?php
    session_start();
?>
<?php include("header.php") ?>

	<section style="padding-top:95px;">
			<div class="row">
			<h3 style="text-align:center;" class="header-border">Frequently Asked Questions</h3><br>
				<div class="col-sm-6">
				  <ul class="bullet">
					<li><h3>What is CBRS ?</h3></li>
					 <p>"CBRS", Commit Based Regression System optimizes regression at any given time, based on commits.</p>
				  </ul>
				</div>
				<div class="col-sm-6">
				 <ul class="bullet">
					<li><h3>What CBRS do ?</h3></li>
					 <p>CBRS uses Database mapping against - LCOV report Vs CDETS commit source code.</p>
				  </ul>
				</div>
			</div><br>

			<div class="row">
				<div class="col-sm-6">
				   <ul class="bullet">
					<li><h3>What is LCOV report ?</h3></li>
					 <p>LCOV is a graphical front-end for GCC's coverage testing tool gcov. It collects gcov data for multiple source files and creates HTML pages containing the source code annotated with coverage information. It also adds overview pages for easy navigation within the file structure. LCOV supports statement, function and branch coverage measurement..</p>
				  </ul>
				</div>
				<div class="col-sm-6">
				<ul class="bullet">
					<li><h3>How to generate a LOC report ?</h3></li>
					 <p><a href="https://gcc.gnu.org/onlinedocs/gcc/Gcov.html" style="text-decoration:underline;font-size: 16px;font-weight: 600;" target="_blank">LCOV</a> report is generated by parsing cflow.dat using LOCVR pluggin.<br>LOCVR is open tool, which takes gcov.files as input and using nextgen-html publish in hmlt format.</p>
				  </ul>
				</div>
			</div><br>



			<div class="row">
				<div class="col-sm-6">
				   <ul class="bullet">
					<li><h3>What is cflow ?</h3></li>
					 <p>CFLOW is code coverage tool to help users determine which source statements of the router software have been executed and how many times.<br>Users can find coverage at the level of statement or function or module or the entire system.</p>
				  </ul>
				</div>
				<div class="col-sm-6">
				<ul class="bullet">
					<li><h3>How cflow works ? </h3></li>
					 <p>- dpe-config files as input : which tells what modules to instrument on what object.</p>
					 <p>-<a href="https://gcc.gnu.org/onlinedocs/" style="text-decoration:underline;font-size: 16px;font-weight: 600;" target="_blank">GCC</a> : compiles the dpe-config, parses the cflow-make file & build an <a style="text-decoration:underline;font-size: 16px;font-weight: 600;" target="_blank" href="http://wwwin-eng.cisco.com/Eng/SET/SES/User_Docs/Cflow_how_to.htm@latest">Instrument</a> cflow image.
						-<a href="http://gcovr.com/guide.html" style="text-decoration:underline;font-size: 16px;font-weight: 600;" target="_blank">LCOV</a> : cflow collected data, cflow report as input and using next-genhtml dislays converged coverage report.</p>
				  </ul>
				</div>
			</div>
			<div class="row">
				 <div class="col-sm-12" style="text-align:center;">
					<img src="images/cflow_struc.jpg">
				 </div>
			</div>

	</section>
		</div>




    <!-- jQuery -->
    <script src="jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Custom Theme Scripts -->

<script src="js/custom.js"></script>

  </body>
</html>
