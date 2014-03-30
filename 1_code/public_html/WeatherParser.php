<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<?php
/* Wunderground API Weather Parser
 * Retrieve XML from Wunderground and store relevant information into MySQL database
 * Access and retrieve $url
 * Retrieve current date and time
 * Parse current weather condition and temperature
 * Insert recordDate, recordTime, weather, and temp into weatherData */
include 'db_connect.php';
include 'functions.php';
date_default_timezone_set('America/New_York');
$nl = "<br /><br />";

$url = "http://api.wunderground.com/api/9dfec0046b8e4547/conditions/q/NY/New_York.xml";
$response = simplexml_load_file($url);
echo "URL: " . $url . $nl;
echo "Parsing:<br />";
$date = date('Y-m-d');
$time = date('H:i');
$check = date('i');

$weather = $response->current_observation->weather;
$temp = $response->current_observation->temp_f;
echo "Date: ". $date . " / Time: " . $time . "<br />";
echo "Currently: " . $weather . ", " . $temp . " F";

$query = "INSERT INTO weatherData(recordDate, recordTime, weather, temp) VALUES ('$date', '$time', '$weather', '$temp')";
if ($check == ('02' || '03' || '04' || '05')) { // Execute only at the beginning of the hour
	$result = mysql_query($query);
	if (!$result) {
		echo $nl . "INSERT INTO Failed";
	} else {
		echo $nl . "INSERT INTO Success";
	}
}

?>
</html>