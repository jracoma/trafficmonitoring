<?php
$nl = "<br /><br />";
/* WeatherCondition()
 * Condense weather conditions into five main categories:
 * - Clear, Cloudy, Fog, Rain, Snow
 * If $weather not recognized, $weather returned */
function WeatherCondition($weather) {
	if ($weather == 'Clear') return "Clear";
	else if ($weather == 'Fog' || $weather == 'Haze') return "Fog";
	else if ($weather == 'Rain' || $weather == 'Heavy Rain' || $weather == 'Light Rain') return "Rain";
	else if ($weather == 'Snow' || $weather == 'Light Snow' || $weather == 'Heavy Snow') return "Snow";
	else if ($weather == 'Mostly Cloudy' || $weather == 'Partly Cloudy' || $weather == 'Cloudy' || $weather == 'Overcast' || $weather == 'Scattered Clouds') return "Cloudy";
	else return $weather;
}

/* GetZoneCoords()
 * Accept integer zone number and return NW, NE, SE, SW coordinates in an array
 * Zone 0: Overall Coverage Area, Zone 1:100 */
function GetZoneCoords($zone) {
	/* N - S */
	$latValues = array(40.9948872, 40.94153888, 40.88819056, 40.83484224, 40.78149392, 40.7281456, 40.67479728, 40.62144896, 40.56810064, 40.51475232, 40.461404);
	/* W - E */
	$lngValues = array(-74.50699, -74.42442234, -74.34185468, -74.25928702, -74.17671936, -74.0941517, -74.01158404, -73.92901638, -73.84644872, -73.76388106, -73.6813134);


	var_dump($latValues);
	var_dump($lngValues);
}

/* GetNearest10()
 * Generates current time and rounds up to nearest 10th minute */
function GetNearest10() {
	$minute = Date('i');

	if (substr($minute, -1)) {
		$minute = substr(Date('i'), -1);
		$minute = 10 - $minute;
		return Date('H:i', strtotime("+$minute minutes"));
	} else {
		return Date('H:i');
	}
}

/* IsHighway()
 * Accepts string description of incident
 * Returns true if in coverage area, false if not */
function IsHighway($desc) {
	$highways = array("NJ-", "US-", "NY-", "I-", "Garden", "Turnpike", "Parkway", "Pkwy", "Pky", "Expy", "George Washington", "Lincoln Tunl", "Brooklyn Brg", "Brooklyn Battery Tunl", "Queensboro", "Fdr", "F D R", "Williamsburg", "Manhattan Brg", "Harlem River", "Outerbridge Crossing", "Triborough Bridge");

	foreach ($highways as $highway) {
		$pos = strpos($desc, $highway);
		if ($pos != false) {
			return true;
		}
	}

	return false;
}

/* GetHighwayName()
 * Retrieve highway name from shortDesc */
function GetHighwayName($desc) {
	$highways = array("NJ-", "US-", "NY-", "I-", "Garden", "Turnpike", "Parkway", "Pkwy", "Pky", "Expy", "George Washington", "Lincoln Tunl", "Brooklyn Brg", "Brooklyn Battery Tunl", "Queensboro", "Fdr", "F D R", "Williamsburg", "Manhattan Brg", "Harlem River", "Outerbridge Crossing", "Triborough Bridge");

	$token = explode(":", $desc);

	$highway = trim($token[0]);

	if ($highway[strlen($highway) - 1] == 'B') {
		$highway = substr(trim($token[0]), 0, -4);
	}

	return $highway;
}

/* ParseHighwayName()
 * Retrieve highway name from route instructions */
function ParseHighwayName($desc) {
	$highways = array("NJ-", "US-", "NY-", "I-", "Garden", "Turnpike", "Parkway", "Pkwy", "Pky", "Expy", "George Washington", "Lincoln Tunl", "Brooklyn Brg", "Brooklyn Battery Tunl", "Queensboro", "Fdr", "F D R", "Williamsburg", "Manhattan Brg", "Harlem River", "Outerbridge Crossing", "Triborough Bridge", "Utopia", "Horace Harding");

	foreach ($highways as $highway) {
		$pos = strpos($desc, $highway);
		if ($pos !== false) {
			$text = preg_split("/[\s]+/", $desc);
			foreach ($text as $val) {
				$res = strpos($val, $highway);
				if ($res !== false) {
					return $val;
				}
			}
		}
	}

	return $highway;
}

/* InsertWeather
 * Insert weather information to corresponding date and time off trafficData */
function InsertWeather() {
	$nl = "<br /><br />";
	$query = "SELECT DISTINCT T.id, T.startDate, T.startTime, W.weather FROM trafficData T INNER JOIN weatherData W ON T.startDate = W.recordDate AND T.startTime = W.recordTime WHERE T.weather LIKE 'NULL'";
	$result = mysql_query($query);
	if (!$result) {
		echo $query . " | Failed " . mysql_errno() . " - " . mysql_error() . $nl;
	} else {
		echo $query . " | Success" . $nl;
	}

	while ($row = mysql_fetch_array($result)) {
		$id = $row['id'];
		$weather = $row['weather'];
		echo $id . " | " . $row['startDate'] . " @ " . $row['startTime'] . " - " . $weather . " | " .  $nl;

		$query = "UPDATE trafficData SET weather = '$weather' WHERE id = '$id'";
		$result1 = mysql_query($query);
		if (!$result1) {
			echo $query . " | Failed " . mysql_errno() . " - " . mysql_error() . $nl;
		} else {
			echo $query . " | Success" . $nl;
		}
	}
}

/* GetHighwayIncidents
 * Retrieve incidents on specific highway */
function GetHighwayIncidents($highway, $nwLat, $nwLng, $seLat, $seLng, $departureTime, $weather) {
	// echo "GetHighwayIncidents\n";
	// var_dump($highway, $nwLat, $nwLng, $seLat, $seLng);
	// var_dump($departureTime, $weather);
	// var_dump($highway);
	$startDate = date_create('2014-02-11');
	$date = date_create(date('Y-m-d'));
	$diff = date_diff($startDate, $date);
	$diff = $diff->format('%a');

	$incidents = array();
	$severity = 0;

	if (ctype_alpha($departureTime)) {
		if ($departureTime == 'e' && $weather == 'all') {
			$query = "SELECT id, lat, lng, severity, weather FROM trafficData WHERE highway LIKE '$highway'";
		} else if ($departureTime == 'a' && $weather == 'all') {
			$query = "SELECT id, lat, lng, severity, weather FROM trafficData WHERE highway LIKE '$highway' AND startTime > '00:00:00' AND startTime < '06:00:00'";
		} else if ($departureTime == 'a' && $weather != 'all') {
			$query = "SELECT id, lat, lng, severity, weather FROM trafficData WHERE highway LIKE '$highway' AND startTime > '00:00:00' AND startTime < '06:00:00' AND weather LIKE '$weather'";
		} else if ($departureTime == 'b' && $weather == 'all') {
			$query = "SELECT id, lat, lng, severity, weather FROM trafficData WHERE highway LIKE '$highway' AND startTime > '06:00:00' AND startTime < '12:00:00'";
		} else if ($departureTime == 'b' && $weather != 'all') {
			$query = "SELECT id, lat, lng, severity, weather FROM trafficData WHERE highway LIKE '$highway' AND startTime > '06:00:00' AND startTime < '12:00:00' AND weather LIKE '$weather'";
		} else if ($departureTime == 'c' && $weather == 'all') {
			$query = "SELECT id, lat, lng, severity, weather FROM trafficData WHERE highway LIKE '$highway' AND startTime > '12:00:00' AND startTime < '18:00:00'";
		} else if ($departureTime == 'c' && $weather != 'all') {
			$query = "SELECT id, lat, lng, severity, weather FROM trafficData WHERE highway LIKE '$highway' AND startTime > '12:00:00' AND startTime < '18:00:00' AND weather LIKE '$weather'";
		} else if ($departureTime == 'd' && $weather == 'all') {
			$query = "SELECT id, lat, lng, severity, weather FROM trafficData WHERE highway LIKE '$highway' AND startTime > '18:00:00' AND startTime < '24:00:00'";
		} else if ($departureTime == 'd' && $weather != 'all') {
			$query = "SELECT id, lat, lng, severity, weather FROM trafficData WHERE highway LIKE '$highway' AND startTime > '18:00:00' AND startTime < '24:00:00' AND weather LIKE '$weather'";
		} else if ($departureTime == 'e' && $weather != 'all') {
			$query = "SELECT id, lat, lng, severity, weather FROM trafficData WHERE highway LIKE '$highway' AND weather LIKE '$weather'";
		}
	} else {
		$query = "SELECT id, lat, lng, severity, weather FROM trafficData WHERE highway LIKE '$highway'";
	}

	$result = mysql_query($query);
	// $incidentsCount = mysql_num_rows($result);
	// var_dump($query);

	while ($row = mysql_fetch_array($result)) {
		$res = array(
			"id" 		=> $row['id'],
			"lat"		=> $row['lat'],
			"lng"		=> $row['lng'],
			"severity"	=> $row['severity'],
			"weather" 	=> $row['weather']
			);
		// var_dump($res);
		// array_push($incidents, $row);
		// echo "Row\n";
		// var_dump($row);
		// echo $row['lat'] . " / " . $row['lng'] . "\n";
		$check = Contains($nwLat, $nwLng, $seLat, $seLng, $row['lat'], $row['lng']);
		// var_dump($check);
		if ($check) {
			$severity = $severity + $row['severity'];
			array_push($incidents, $res);
		}
	}

	$incidentsCount = count($incidents);

	if ($severity == 0) {
		return 0;
	}
	// var_dump($severity, $severityCount);
	// $severity = $severity / ($severityCount * 4);
	$incidentChance = ($incidentsCount / $diff) * 100;
	$severity = $severity / $incidentsCount;
	if ($incidentChance > 55) {
		$color = "red";
	} else if ($incidentChance > 40) {
		$color = "orange";
	} else if ($incidentChance > 25) {
		$color = "yellow";
	} else if ($incidentChance > 10) {
		$color = "green";
	} else {
		return 0;
	}
	// var_dump($incidentsCount, $diff, $incidentChance, $severity);
	$result = array(
		"lat" 				=> $incidents[0]['lat'],
		"lng" 				=> $incidents[0]['lng'],
		"incidentChance"	=> $incidentChance,
		"severity"			=> $severity,
		"color"				=> $color
		);
	// var_dump($severity);
	// var_dump($incidents[0]);
	// var_dump($result);
	return $result;
}

/* Contains
 * Checks whether a given lat/lon is inside a given box */
function Contains($nwLat, $nwLng, $seLat, $seLng, $lat, $lng) {
	// echo "Contains\n";
	// var_dump($nwLat, $nwLng, $seLat, $seLng, $lat, $lng);
	if ($lat > $nwLat || $lat < $seLat || $lng < $nwLng || $lng > $seLng) {
		return false;
	}
	return true;
}

/* GetCount
 * Return number of rows in trafficData */
function GetCount() {
	$query = "SELECT COUNT(*) FROM trafficData";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$count = $row['COUNT(*)'];

	return $count;
}

/* GetTimeRange
 * Return time range given time
 * 0: 0-6 / 1: 6-12 / 2: 12-18 / 3: 18-24 */
function GetTimeRange($time) {
	$time = substr($time, 0, -3);
	if ($time >= 0 && $time < 6) {
		$range = 0;
	} else if ($time >= 6 && $time < 12) {
		$range = 1;
	} else if ($time >= 12 && $time < 18) {
		$range = 2;
	} else {
		$range = 3;
	}

	// var_dump($time, $range);
	return $range;
}

/* AddAlert
 * Add alert to database */
function AddAlert($username, $alertDate, $alertTime, $sLocation, $dLocation, $alertMethod, $alertAt) {
	$query = "INSERT INTO alertsDatabase(username, alertDate, alertTime, sLocation, dLocation, alertMethod, alertAt) VALUES('$username', '$alertDate', '$alertTime', '$sLocation', '$dLocation', '$alertMethod', '$alertAt')";
	$result = mysql_query($query);
}

/* GetUsername
 * Retrieve username given email address */
function GetUsername($email) {
	$query = "SELECT username FROM userDatabase WHERE email = '$email'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$username = $row['username'];

	return $username;
}

?>