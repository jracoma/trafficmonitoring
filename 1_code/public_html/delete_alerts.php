<?php

include 'db_connect.php';

/* delete the given alerts */
function remove_alerts($alertId, $con)
{
	$del = "delete from alertsDatabase where id='";
	$del = $del . $alertId . "'";

	mysql_query($del, $con) 
		or die('Delete alerts failed : '. mysql_error());
}

$aid = $_GET['id'];

// echo $aid;
/* delete given id alerts */
remove_alerts($aid, $con);

header('Location: ManageAlerts.php');
?>
