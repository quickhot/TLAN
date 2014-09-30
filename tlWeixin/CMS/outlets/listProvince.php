<?php
	include '../dbInc.php';
	include '../class/tsDAO.php';
	include_once '../lib/loginStatus.php';
	$tsDAO = new tsDAO($dbHost, $dbUser, $dbPass, $dbname,$dbPort);
	$province = $tsDAO -> getProvinceList();

	echo "<select>";
	foreach ($province AS $arr){
		echo "<option value=\"".$arr['id']."\">".$arr['province']. "</option>";
	}
	/*
	while ($roomName = $lhcDAO -> getListRoom($db, $ESId)) {
		echo "<option value=\"".$roomName['roomId']."\">".$roomName['roomName']. "</option>";
	}
	*/
	echo "</select>";
?>