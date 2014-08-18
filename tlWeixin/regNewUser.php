<?php
header('Content-type: text/plain');
$wxId=$_GET['wxId'];
include_once 'conf/mysql.php';
include_once 'classes/General.class.php';
include_once 'classes/MysqlDB.class.php';

$ret = array();
$openId = General::wldecode(urldecode($wxId));
if ($openId) {
	$newConn = new MysqlDB(DBHOST, DBUSER, DBPASS, DBNAME);
	if ($newConn) {
		echo $newConn->addNewUser($openId);
	}
}