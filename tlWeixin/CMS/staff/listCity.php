<?php
	include '../dbInc.php';
	include '../class/tsDAO.php';
	include_once '../lib/loginStatus.php';
	$tsDAO = new tsDAO($dbHost, $dbUser, $dbPass, $dbname,$dbPort);
	$provinceId = $_GET['provinceId'];
	$citis = $tsDAO -> getCitisList($provinceId);

	//echo "<select>";
	echo "<option value=\"0\">请选择</option>";
	foreach ($citis AS $arr){
		echo "<option value=\"".$arr['id']."\">".$arr['city']. "</option>";
	}
	/*
	while ($roomName = $lhcDAO -> getListRoom($db, $ESId)) {
		echo "<option value=\"".$roomName['roomId']."\">".$roomName['roomName']. "</option>";
	}
	*/
	//echo "</select>";
?>