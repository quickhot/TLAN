<?php
	include '../dbInc.php';
	include '../class/tsDAO.php';
	include_once '../lib/loginStatus.php';
	$tsDAO = new tsDAO($dbHost, $dbUser, $dbPass, $dbname,$dbPort);
	$cityId = $_GET['cityId'];
	$counties = $tsDAO -> getCountyList($cityId);

	//echo "<select>";
	foreach ($counties AS $arr){
		echo "<option value=\"".$arr['id']."\">".$arr['county']. "</option>";
	}
	/*
	while ($roomName = $lhcDAO -> getListRoom($db, $ESId)) {
		echo "<option value=\"".$roomName['roomId']."\">".$roomName['roomName']. "</option>";
	}
	*/
	//echo "</select>";
?>