<?php
	include '../dbInc.php';
	include '../class/tsDAO.php';
	include_once '../lib/loginStatus.php';
	$tsDAO = new tsDAO($dbHost, $dbUser, $dbPass, $dbname,$dbPort);
	$agent = $tsDAO -> getAgentList();

	echo "<select>";
	foreach ($agent AS $arr){
		echo "<option value=\"".$arr['id']."\">".$arr['agentName']. "</option>";
	}
	/*
	while ($roomName = $lhcDAO -> getListRoom($db, $ESId)) {
		echo "<option value=\"".$roomName['roomId']."\">".$roomName['roomName']. "</option>";
	}
	*/
	echo "</select>";
?>