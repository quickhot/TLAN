<?php
	include '../dbInc.php';
	include '../class/tsDAO.php';
	include_once '../lib/loginStatus.php';
	$tsDAO = new tsDAO($dbHost, $dbUser, $dbPass, $dbname,$dbPort);
	$agentId = $_GET['agentId'];
	$outlets = $tsDAO -> getOutletsList($agentId);

	//echo "<select>";
	echo "<option value=\"0\">请选择</option>";
	foreach ($outlets AS $arr){
		echo "<option value=\"".$arr['id']."\">".$arr['outletName']. "</option>";
	}
	/*
	while ($roomName = $lhcDAO -> getListRoom($db, $ESId)) {
		echo "<option value=\"".$roomName['roomId']."\">".$roomName['roomName']. "</option>";
	}
	*/
	//echo "</select>";
?>