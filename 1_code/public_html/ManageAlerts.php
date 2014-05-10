<?php

/* Traffic Monitor Index Page
 * Display main user interface
 * Accept Inputs:
 * - Starting Location
 * - Destination Location
 * - Time of Departure/Arrival
 * - Date (Optional)
 * - Set Alert
 * - Alert Method (Optional)
 * - Email Address/Phone Number
 * Calculates best route using Google Maps API and provides historical data */
include 'db_connect.php';
include 'functions.php';
include 'alerts_manage.php';
date_default_timezone_set('America/New_York');
$nl = "<br /><br />";

require_once ("./include/membersite_config.php");

if (!$fgmembersite->CheckLogin()) {
	$fgmembersite->RedirectToURL("http://jracoma.com/trafficmonitor/register.php");
	exit;
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<title>Traffic Monitoring - Spring 2014</title>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/bootstrap.css" media="screen">
	<link rel="stylesheet" href="css/bootstrap.min.css">

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
 <!--[if lt IE 9]>
 <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
 <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
 <![endif]-->

 <style>
 	#mapCanvas {
 		width: 795px;
 		height: 580px;
 	}
 	#control {
 		background: #fff;
 		padding: 5px;
 		font-size: 14px;
 		font-family: Arial;
 		border: 1px solid #ccc;
 		box-shadow: 0 2px 2px rgba(33, 33, 33, 0.4);
 		display: none;
 	}
 	body { padding-top: 0px; }
 </style>
 <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>

<body>
	<!-- Begin Navbar -->
	<div class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<a href="http://jracoma.com/trafficmonitor/Map.php" class="navbar-brand">Traffic Monitoring</a>
				<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div class="navbar-collapse collapse" id="navbar-main">
				<ul class="nav navbar-nav">
					<li>
						<a href="ManageAlerts.php">Manage Alerts</a>
					</li>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					<li><a href='logout.php'>Logout</a></li>
				</ul>

			</div>
		</div>
	</div>
	<!-- End Navbar -->

	<!-- Main Body -->

	<!-- Top Row - GetDirections & Map -->
	<div class="container">
		<div class="jumbotron">
			<div class="bs-docs-grid">

				<div class="row">
					<div class="col-md-12">
						<h2>Get Directions</h2>
						<div>
							<table width="100%">
								<tr bgColor="#F0F0F0"><td colspan=5 align=center>Alerts List</td><tr>
									<tr><td>Date</td><td>Time</td><td>Start</td><td>Destination</td><td>Choice</td><tr>
										<?php
										$email = $_SESSION['email_of_user'];
										$username = GetUsername($email);

										$lines = list_alerts($username, $con);
										foreach($lines as $tr)
											echo $tr;
										?>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!-- End Top Row - GetDirections & Map -->

				<!-- End Main Body -->
				<!-- Bootstrap core JavaScript ================================================== -->
				<!-- Placed at the end of the document so the pages load faster -->
				<script type="text/javascript" language="javascript" src="js/bootstrap.min.js"></script>
				<script type="text/javascript" language="javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
				<script type="text/javascript" language="javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.js"></script>
			</body>
			</html>