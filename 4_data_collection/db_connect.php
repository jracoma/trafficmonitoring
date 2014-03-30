<?php
define("HOST", "localhost"); // The host you want to connect to.
define("USER", "jracomac_user123"); // The database username.
define("PASSWORD", "pword123"); // The database password.

$con = mysql_connect(HOST, USER, PASSWORD);
if (!$con) {
	die('Could not connect: ' . mysql_error());
}

mysql_query('USE jracomac_monitoring', $con);
?>