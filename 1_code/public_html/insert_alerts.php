<?php

include 'db_connect.php';
include 'alerts_manage.php';

$rec = array();
$rec[] = "root";
$rec[] = "2014-05-09";
$rec[] = "12:33:09";
$rec[] = "Beijing";
$rec[] = "ShangHai";
$rec[] = "email";

print_r($rec);

insert_alerts($rec, $con);

header("Location: alerts_page.php");
?>
