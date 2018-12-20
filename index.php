<?php
    session_start();
    if($_SESSION["username"] != ""){
        header("location:home.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/cisco_logo.png" type="image/ico" />
    <title>COBR</title>
    <!-- Bootstrap -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="css/custom.css" rel="stylesheet">
  </head>

  <body class="nav-md img-background">
    <div class="container body">
        <div class="main_container">
            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="navbar nav_title" style="border: 0;">
                            <a href="index.php" class="site_title1"><img src="images/cisco_logo.png" style="width: 100%;"></a>
                        </div>
                        <div class="navbar nav_title hidden-xs cisco-login-header">
                            <h2 class="main-head-text">COMMIT BASED REGRESSION SYSTEM</h2>
                        </div>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->
        </div>
    </div>
	<section class="mobile-section" style="padding-top:225px;">
		<div class="container">
			<div class="row main">
				<div class="col-sm-12">
				<div class="main-login main-center">
					<form class="form-horizontal" id="loginFormSubmit" method="post">
						<span id="valid-login-report"></span>
						<div class="form-group form-group-cisco ">
							<label for="name" class="cols-sm-2 control-label label-cisco">User Name</label>
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control input-form-control" name="username" id="username"  placeholder="Enter User Name" autocomplete="off" required/>
								</div>
							</div>
						</div>

						<div class="form-group form-group-cisco">
							<label for="email" class="cols-sm-2 control-label label-cisco">Password</label>
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-key" aria-hidden="true"></i></span>
									<input class="form-control input-form-control" type="password" name="password" id="password"   placeholder="Password" autocomplete="off" required/>
								</div>
							</div>
						</div>
						<div class="form-group ">
							<button type="submit" class="btn btn-primary btn-lg btn-block login-button">Submit</button>
						</div>
					</form>
				</div>
				</div>
			</div>
		</div>
	</section>




    <!-- jQuery -->
    <script src="jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Custom Theme Scripts -->

   <script>

	$("#loginFormSubmit").submit(function(event) {
		event.preventDefault();
		//alert(textFile);
		var loginFormData = [];
		loginFormData[0] = $("input#username").val();
		loginFormData[1] = $("input#password").val();
		if(loginFormData[0] == '' && loginFormData[0] == ''){
			document.getElementById("valid-login-report").innerHTML = "Please Enter Valid Details";
		}else{
			unique = JSON.stringify(loginFormData);
			$.ajax({
				url:'logincheckup.php',
				type:'POST',
				data:{json:unique},
				success:function(data){
					if(data.indexOf('true') != -1){
						location.href = "home.php";
					}else {
                        alert("Invalid Username/Password");
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


</script>
<script src="js/custom.js"></script>

  </body>
</html>
