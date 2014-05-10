<?php

include 'mail_functions.php';

/***
 * alert time judgement:
 * between [now, now + 10]
 */
function valid_alerts_time($altDate, $altTime)
{
	$now = strtotime(date("y-m-d h:i:s"));
	$end = strtotime($altDate . " " . $altTime); 

	if ($end - $now > 86400*10 || $end < $now)
	{
		return false;
	}

	return true;
}

/* get max id */
function get_alerts_maxid($con)
{
	$sel = "select max(id) from alertsDatabase";
	$result = mysql_query($sel, $con);

	$maxid = mysql_fetch_row($result);

	return $maxid[0];
}

/***
 * Array(alert) = {username, date, time, sloc, dloc, altmode}
 */
function insert_alerts($pAlert, $con)
{
	/* get next ID number */
	$maxNo = get_alerts_maxid($con);
	$maxNo = $maxNo + 1;

	//echo $maxNo;

	/* valid alert time */
	$altDate = $pAlert[1];
	$altTime = $pAlert[2];

	if (!valid_alerts_time($altDate, $altTime))
	{
		return -1;
	}

	$ins = "insert into alertsDatabase(id, username, alertDate, 
		alertTime, sLocation, dLocation, alertMethod) values( ";

	$ins = $ins . "'" . $maxNo . "', ";

	foreach ($pAlert as $item)
	{
		$ins = $ins . "'" . $item . "'";
		if ($item != end($pAlert))
		{
			$ins = $ins . ", ";
		}
	}

	$ins = $ins . ")";

	//echo $ins;

	mysql_query($ins, $con) 
		or die('Add alerts failed : '.mysql_error());

	return $maxNo;
}

function list_alerts($name, $con)
{
	$qry = "select * from alertsDatabase where username='";
	$qry = $qry . $name . "' order by alertDate, alertTime";

	$result = mysql_query($qry, $con);
	//$row = mysql_fetch_row($result);

	$num = mysql_num_rows($result);

	$i = 0;
	$line = array();
	
	while ($i < $num)
	{
		$id = mysql_result($result, $i, "id");
		$alertDate = mysql_result($result, $i, "alertDate");
		$alertTime = mysql_result($result, $i, "alertTime");
		$sLocation = mysql_result($result, $i, "sLocation");
		$dLocation = mysql_result($result, $i, "dLocation");
		$alertMethod = mysql_result($result, $i, "alertMethod");

		$line[] = "<tr><td>$alertDate</td><td>$alertTime</td><td>$sLocation</td><td>$dLocation</td><td><a href='delete_alerts.php?id=$id'/>Del</td></tr>";

		$i++;
	}

	return $line;
}

/* send alerts every hour 
   1. send to the user where (now, alertitme) < 1hour
*/
function send_alerts($con)
{
	$today = date("Y-m-d");
	$qry = "select * from  alertsDatabase 
		where alertDate = '$today'
		and alertMethod = 'email'
		order by alertDate, alertTime desc";
	$result = mysql_query($qry, $con);

	$i = 0;
	$num = mysql_num_rows($result);

	while ($i < $num)
	{
		$altDate = mysql_result($result, $i, "alertDate");
		$altTime = mysql_result($result, $i, "alertTime");
		$altsLoc = mysql_result($result, $i, "sLocation");
		$altdLoc = mysql_result($result, $i, "dLocation");

		$content = "Departure Time: ". $altDate . " ".$altTime."<br>";
		$content = $content."Start Loaction: ".$altsLoc."<br>";
		$content = $content."Destination: ".$altdLoc."<br>";

		//echo date("Y-m-d h:i:s")."<br>";
		$now = strtotime(date("Y-m-d h:i:s"));
		$due = strtotime($altDate." ".$altTime);

		$username = mysql_result($result, $i, "username");

		/* get email address from userDatabase */
		$aquery = "select * from userDatabase where 
			username='$username'";
		$aret = mysql_query($aquery, $con);
		$anum = mysql_num_rows($result);
		if ($anum > 1)
			$addr = mysql_result($aret, 0, "emailAddress");

		/* send mail */
		if ($due > $now && $due - $now < 3600)
		{
			mail_send($addr, $username, $content);
			echo "send success.<br>";
		}

		$i++;
	}
}

?>
