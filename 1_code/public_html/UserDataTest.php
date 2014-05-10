<html>
<body>
<?php 
include 'db_connect.php';

$query = "SELECT * FROM userDatabase ORDER by name" ; $result = mysql_query($query);

$num = mysql_num_rows($result); mysql_close();?>

<table border="1" cellspacing="5" cellpadding="1">
<tr> 
<td> <b> <center>
<font face="Arial, Helvetica, sans-serif">name</font>
</center> </b> </td> 
<td> <b> <center>
<font face="Arial, Helvetica, sans-serif">email</font>
</center> </b> </td>
<td> <b> <center>
<font face="Arial, Helvetica, sans-serif">username</font>
</center> </b> </td> 
<td> <b> <center>
<font face="Arial, Helvetica, sans-serif">password</font>
</center> </b> </td>
</tr>
<?php $i=0; while ($i < $num) {
$name=mysql_result($result,$i,"name");
$email=mysql_result($result,$i,"email");
$username=mysql_result($result,$i,"username");
$password=mysql_result($result,$i,"password");?>
<tr>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $name; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $email; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $username; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $password; ?></font>
</td>
</tr>
<?php
$i++;}?>

</body>
</html>