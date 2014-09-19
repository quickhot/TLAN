<?php
include '../dbInc.php';
include '../class/tsDAO.php';
include_once '../lib/loginStatus.php';
$agent['id'] = $_POST['id'];
$agent['agentName'] = $_POST['agentName'];
$agent['address'] = $_POST['address'];
$oper = $_POST['oper'];

$tsDAO = new tsDAO($dbHost,$dbUser,$dbPass,$dbname);
$tsDAO -> agentEdit($db,$oper,$agent);
?>