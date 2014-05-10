<?php
/* MapQuest API Traffic Parser
 * Retrieve XML from MapQuest and store relevant information into MySQL database
 * - Accepts NW and SE */
include 'db_connect.php';
include 'functions.php';
date_default_timezone_set('America/New_York');
$nl = "<br /><br />";

/* Retrieve results from AJAX call */
$steps = array();
$highways = array();
$incidents = array();
$steps = $_POST['steps'];
$boundingBoxes = $_POST['boundingBoxes'];
$departureTime = $_POST['departureTime'];
$weather = $_POST['weather'];
$saveAlert = $_POST['saveAlert'];

/* SaveAlert if set */
if ($saveAlert == 'saveAlert') {
	$sLocation = $_POST['sLocation'];
	$dLocation = $_POST['dLocation'];
	$alertDate = $_POST['alertDate'];
	$alertTime = $_POST['alertTime'];
	if (isset($_POST['emailAddress'])) {
		$alertAt = $_POST['emailAddress'];
		$alertMethod = "email";
	} else if (isset($_POST['phoneNumber'])) {
		$alertAt = $_POST['phoneNumber'];
		$alertMethod = "phone";
	}
	$email = $_POST['email'];
	$username = GetUsername($email);
	// $query = "SELECT username FROM userDatabase WHERE email = '$email'";
	// $result = mysql_query($query);
	// $row = mysql_fetch_assoc($result);
	// $username = $row['username'];

	AddAlert($username, $alertDate, $alertTime, $sLocation, $dLocation, $alertMethod, $alertAt);
}

/* Parse through steps, remove HTML, retrieve highway name */
foreach ($steps as $step) {
	$text = strip_tags($step);
	if (IsHighway($text)) {
		$highway = ParseHighwayName($text);
		array_push($highways, $highway);
	}
}

/* Unique only highways */
$temp = array_unique($highways);
$highways = array_values($temp);
sort($highways);

if ($highways) {
	$count = GetCount();
}

// var_dump($highways);
foreach ($boundingBoxes as $box) {
	$nwLat = $box['NWLat'];
	$nwLng = $box['NWLng'];
	$seLat = $box['SELat'];
	$seLng = $box['SELng'];
	// var_dump($box);
	// $test = Contains($nwLat, $nwLng, $seLat, $seLng, "40.55", "-74.5");
	// echo "test: ";
	// var_dump($test);
	foreach ($highways as $highway) {
		$incident = GetHighwayIncidents($highway, $nwLat, $nwLng, $seLat, $seLng, $departureTime, $weather);
		if ($incident) {
			// echo "Incidents for: " . $highway . "\n";
			// print_r(json_encode($incident));
			// echo "\n";
			array_push($incidents, $incident);
		}
		// b
	}
}

$output = json_encode($incidents);
print_r($output);
?>