<?php
	include '../dbInc.php';
	include '../class/tsDAO.php';
	include_once '../lib/loginStatus.php';
	$tsDAO = new tsDAO($dbHost, $dbUser, $dbPass, $dbname,$dbPort);
	$brand = $tsDAO -> getBrandList();

	echo "<select>";
	foreach ($brand AS $arr){
		echo "<option value=\"".$arr['id']."\">".$arr['brandName']. "</option>";
	}
	echo "</select>";
?>