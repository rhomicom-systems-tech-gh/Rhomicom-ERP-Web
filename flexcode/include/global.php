<?php
	/*ini_set("display_errors", 0);
	error_reporting(0);*/

	$base_path		= "http://flexcode.yilostargh.com/";
	$db_name		= "demo_flexcodesdk";
	$db_user		= "ysbnkng";
	$db_pass		= "Password1";
	$db_host		= "localhost:3306";
	$time_limit_reg = "15";
	$time_limit_ver = "10";

	$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
	if (!$conn) die("Connection for user $db_user refused!");
	//mysqli_select_db($db_name, $conn) or die("Can not connect to database!");
?>