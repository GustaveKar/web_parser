<?php if (isset($_GET['source'])) die(highlight_file(__FILE__, 1)); ?>

<?php

// config.php
$db_user = "karengeg";
$db_password = "cj_Dm454p++gWZ";
$db_host = "mysql.iro.umontreal.ca";
$db_name = "karengeg_tp3_db";


$conn = mysql_connect( $db_host, $db_user, $db_password);
if (!$conn) 
	die("probleme de connection ". mysql_error());
	
mysql_select_db($db_name) or die("probleme de selection " . mysql_error());


?>  




