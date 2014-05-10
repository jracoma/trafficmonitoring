<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<?php
/* MapQuest API Traffic Parser
 * Retrieve XML from MapQuest and store relevant information into MySQL database
 * - Accepts NW and SE */
include 'db_connect.php';
include 'functions.php';
date_default_timezone_set('America/New_York');
$nl = "<br /><br />";

$url = "http://www.mapquestapi.com/traffic/v2/incidents?key=Fmjtd%7Cluur216t2u%2Ca5%3Do5-90tnlz&callback=handleIncidentsResponse&boundingBox=40.9948872,-74.50699,40.461404,-73.6813134&filters=construction,incidents,congestion&inFormat=kvp&outFormat=xml";
$response = simplexml_load_file($url);
echo "URL: " . $url . $nl;
echo "Parsing:<br />";

$incident = $response->Incidents->Incident;
foreach ($incident as $val) {
	$test = 0;
	$incidentID = $val->id;
	$lat = $val->lat;
	$lng = $val->lng;
	$type = $val->type;
	$severity = $val->severity;
	$start = $val->startTime;
	$startDate = substr($start, 0, 10);
	$hour = substr($start, 11, 2);
	$startTime = date('H:i:s', mktime($hour, 0, 0, 0, 0, 0));
	$day = date('l', strtotime($startDate));
	$end = $val->endTime;
	$endDate = substr($end, 0, 10);
	$endTime = substr($end, 11, 5);
	$dateTest = strtotime($endDate) - strtotime($startDate);
	$shortDesc = $val->shortDesc;
	$fullDesc = $val->fullDesc;
	$distance = $val->distance;
	$delayFT = $val->delayFromTypical;
	$delayFFF = $val->delayFromFreeFlow;
	$highway = GetHighwayName($shortDesc);
	$result = IsHighway($fullDesc);
	$weather = "NULL";
	echo "<br />Test: " . $dateTest . " -- " . $incidentID . " | " . $lat . " | " . $lng . " | " . $type . " | " . $severity . " | " . $startDate . " | " . $day . " | " . $startTime . " | " . $endDate . " | " . $endTime . " | " . $shortDesc . " | " . $fullDesc . " | " . $distance . " | " . $delayFT . " | " . $delayFFF . " | " . $weather. " | " . $highway;
	if ($result == false) {
		echo "<br />Not Found!" . $nl;
		continue;
	}
	if ($dateTest > 172800) {
		echo "<br /> Over 2 days, Ignoring." . $nl;
		continue;
	}

	$query = "INSERT INTO trafficData(id, lat, lng, type, severity, startDate, day, startTime, endDate, endTime, shortDesc, fullDesc, distance, delayFromTypical, delayFromFreeFlow, weather, highway) VALUES('$incidentID', '$lat', '$lng', '$type', '$severity', '$startDate', '$day', '$startTime', '$endDate', '$endTime', '$shortDesc', '$fullDesc', '$distance', '$delayFT', '$delayFFF', '$weather','$highway')";
	$result = mysql_query($query);
	if (!$result) {
		echo "<br />" . "INSERT INTO Failed " . mysql_errno() . " - " . mysql_error() . $nl;
	} else {
		echo "<br />" . "INSERT INTO Success" . $nl;
	}

}

?>
</html>