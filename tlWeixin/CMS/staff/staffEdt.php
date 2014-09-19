<?php
include '../dbInc.php';
include '../class/tsDAO.php';
include_once '../lib/loginStatus.php';
$userInfo['id']			= $_POST['id'];
// $userInfo['roomId']	    = $_POST['roomId'];
$userInfo['alias']		= $_POST['alias'];
$userInfo['gender']		= $_POST['gender'];
$userInfo['mobileNo']	= $_POST['mobileNo'];
$userInfo['IDCardNo']	= $_POST['IDCardNo'];
$userInfo['identity']	= 3;
$oper = $_POST['oper'];

$tsDAO = new tsDAO($dbHost,$dbUser,$dbPass,$dbname);
$tsDAO -> issetMobileNo($db,$userInfo['mobileNo']);
$result = $tsDAO->getStaffRoomId($db, $ESId,$userInfo['mobileNo']);
$userInfo['roomId']=$result;
$tsDAO -> staffEdit($db,$oper,$userInfo);
?>