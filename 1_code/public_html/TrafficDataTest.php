<html>
<body>
<?php 
include 'db_connect.php';

$query = "SELECT * FROM trafficData ORDER BY id" ; $result = mysql_query($query);

//WHERE fullDesc NOT REGEXP 'NJ-|US-|NY-|I-|
//|Garden|Turnpike|Parkway|Pkwy|Pky|Expy|George Washington|Lincoln Tunl|Brooklyn Brg|Brooklyn Battery Tunl|Queensboro|
//|Fdr|F D R|Williamsburg|Manhattan Brg|Harlem River|Outerbridge Crossing|Triborough Bridge'

//I-78|I-80|I-87|I-95|I-278|I-287|I-280|I-295|I-495|I-678|I-895|
//|Garden|Turnpike|Parkway|Pkwy|Pky|Expy|George Washington|Lincoln|Brooklyn|Queensboro|Fdr|F D R|Williamsburg|Manhattan Brg|Harlem River|Outerbridge Crossing|Triborough Bridge|
//|US-1|US-9|US-22|US-46|US-202|
//|NJ-3|NJ-4|NJ-5|NJ-10|NJ-17|NJ-18|NJ-19|NJ-20|NJ-21|NJ-23|NJ-24|NJ-67|NJ-82|NJ-124|NJ-139|NJ-208|NJ-440|NJ-495|
//|NY-9|NY-25|NY-27|NY-440

$num = mysql_num_rows($result); mysql_close();?>

<table border="10" cellspacing="15" cellpadding="5">
<tr> 
<td> <b> <center>
<font face="Arial, Helvetica, sans-serif">id</font>
</center> </b> </td> 
<td> <b> <center>
<font face="Arial, Helvetica, sans-serif">lat</font>
</center> </b> </td>
<td> <b> <center>
<font face="Arial, Helvetica, sans-serif">lng</font>
</center> </b> </td> 
<td> <b> <center>
<font face="Arial, Helvetica, sans-serif">type</font>
</center> </b> </td>
<td> <b> <center>
<font face="Arial, Helvetica, sans-serif">severity</font>
</center> </b> </td>
<td> <b> <center>
<font face="Arial, Helvetica, sans-serif">startDate</font>
</center> </b> </td>
<td> <b> <center>
<font face="Arial, Helvetica, sans-serif">day</font>
</center> </b> </td>
<td> <b> <center>
<font face="Arial, Helvetica, sans-serif">startTime</font>
</center> </b> </td>
<td> <b> <center> 
<font face="Arial, Helvetica, sans-serif">endDate</font>
</center> </b> </td>
<td> <b> <center>
<font face="Arial, Helvetica, sans-serif">endTime</font>
</center> </b> </td>
<td> <b> <center>
<font face="Arial, Helvetica, sans-serif">shortDesc</font>
</center> </b> </td>
<td> <b> <center> 
<font face="Arial, Helvetica, sans-serif">fullDesc</font>
</center> </b> </td>
<td> <b> <center> 
<font face="Arial, Helvetica, sans-serif">distance</font>
</center> </b> </td>
<td> <b> <center> 
<font face="Arial, Helvetica, sans-serif">highway</font>
</center> </b> </td>
</tr>
<?php $i=0; while ($i < $num) {$id=mysql_result($result,$i,"id");
$lat=mysql_result($result,$i,"lat");
$lng=mysql_result($result,$i,"lng");
$type=mysql_result($result,$i,"type");
$severity=mysql_result($result,$i,"severity");
$startDate=mysql_result($result,$i,"startDate");
$day=mysql_result($result,$i,"day");
$startTime=mysql_result($result,$i,"startTime");
$endDate=mysql_result($result,$i,"endDate");
$endTime=mysql_result($result,$i,"endTime");
$shortDesc=mysql_result($result,$i,"shortDesc");
$fullDesc=mysql_result($result,$i,"fullDesc");
$distance=mysql_result($result,$i,"distance");
$highway=mysql_result($result,$i,"highway");?>
<tr>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $id; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $lat; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $lng; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $type; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $severity; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $startDate; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $day; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $startTime; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $endDate; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $endTime; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $shortDesc; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $fullDesc; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $distance; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $highway; ?></font>
</td>
</tr>
<?php
$i++;}?>

</body>
</html>