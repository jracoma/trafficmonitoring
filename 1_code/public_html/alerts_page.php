<?php

//include 'alert-functions.php';
include 'db_connect.php';
include 'alerts_manage.php';
include 'functions.php';

require_once ("./include/membersite_config.php");

if (!$fgmembersite->CheckLogin()) {
	$fgmembersite->RedirectToURL("http://jracoma.com/trafficmonitor/register.php");
	exit;
}
?>

<head>
<title>
Alerts Mangement Page
</title>
<style type="text/css">
td{border:solid #add9c0; border-width:0px 1px 1px 0px;}
table{border:solid #add9c0; border-width:1px 0px 0px 1px;}
</style>
</head>

<body>
<form>
<table width="60%">
<tr bgColor="green"><td colspan=5 align=center>Alerts List</td><tr>
<tr><td>Date</td><td>Time</td><td>Start</td><td>Destination</td><td>Choice</td><tr>
<?php
//if (Login())
{
	//$username = $_POST['netID'];
	$email = $_SESSION['email_of_user'];
	$username = GetUsername($email);
	// $username = "root";

	$lines = list_alerts($username, $con);
	foreach($lines as $tr)
		echo $tr;
}
?>
</table>
<!-- <a href="insert_alerts.php">Add Alerts</a> -->
<br>
<!-- <a href="send_alerts.php">Send Alerts</a> -->
</form>
</body>
