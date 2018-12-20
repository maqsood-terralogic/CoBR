
<?php
    session_start();
    $username = $_SESSION["username"];
    if($username == ''){
        header("location:index.php");
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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="css/custom.css" rel="stylesheet">
</head>

<body class="nav-md">
    <div class="container body">
  <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="navbar nav_title" style="border: 0;">
                            <a href="home.php" class="site_title1"><img src="images/cisco_logo.png" style="width: 100%;"></a>
                        </div>
						<div class="navbar nav_title home-btn-cisco" >
								<a href="home.php" id="homeImage"><i class="fa fa-home"></i></a>
						</div>
                        <div class="navbar nav_title hidden-xs cisco-header-text-align">
                            <h2 class="main-head-text">COMMIT BASED REGRESSION SYSTEM</h2>
                        </div>
                        <div class="nav toggle visible-xs">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <ul class="nav navbar-nav navbar-right">
							
                             <li>
                                <div class="profile_cisco clearfix" style="position: relative;right: 23px;">
                                    <div class="profile_info_cisco">
                                        <span id="username-cisco"><?php echo $username;?></span><br>
                                        <a href="logout.php"><i>Logout</i></a>
                                    </div>
									 <div class="profile_pic_image">
										<div id="profileImage"></div>
									  </div>

                                </div>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->
