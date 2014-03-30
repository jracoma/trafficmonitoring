<?php
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

	echo "poop";

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

if (isset($_POST['test'])) {
	echo "BANG BANG";
	// return "test";
}

function Login()
{
	if(isset($_POST['netID'], $_POST['password']))
	{
		$netID = trim($_POST['netID']);
		$password = trim($_POST['password']);

		if (!isset($_SESSION))
		{
			session_start();
		}

		if(CheckLogin($netID,$password))
		{
			// Login success
			$_SESSION['login'] = '1';
			$_SESSION['netID'] = $netID;
			if(CheckIfProf($netID))
			{
				$_SESSION['type'] = 'prof';
			} else if (!CheckIfProf($netID))
			{
				$_SESSION['type'] = 'stud';
			}
		} else {
			return false;
		}
	}
	return true;
}

function CheckLogin($netID,$password)
{
	$query = "SELECT * FROM netUser N WHERE N.netID = '$netID' AND N.password = '$password'";

	$result = mysql_query($query);

	if (!$result || mysql_num_rows($result) <= 0 )
	{
		return false;
	}

	return true;
}

?>