<?php
header ( 'Content-type: text/json' );
$wxId = $_POST ['wxId'];
$verifyCode = $_POST['vCode'];
/*
$wxId = $_GET ['wxId'];
$phoneNo = $_GET ['phoneNo'];
$idCard = $_GET['idCard'];
*/
include_once 'conf/mysql.php';
include_once 'classes/General.class.php';
include_once 'classes/MysqlDB.class.php';
include_once 'classes/ErrInfo.class.php';

$ret = array ();
$openId = General::wldecode ( urldecode ( $wxId ) );
if ($openId) {
	$newConn = new MysqlDB ( DBHOST, DBUSER, DBPASS, DBNAME );
	if ($newConn) {
			$isVerifyCode = $newConn->checkVerifiCode($openId,$verifyCode);
			if ($isVerifyCode<0) {
				$ret['success']=0;
				$ret['errCode']=-7;
			} else {
				$ret['success']=1;
				$ret['errCode']=1;
			}
	} else {
		$ret ['success'] = 0;
		$ret ['errCode'] = -1;
	}
} else {
	$ret ['success'] = 0;
	$ret ['errCode'] = -3;
}
$errInfo = new ErrInfo();
$ret['errInfo'] = $errInfo->getErrInfoByCode($ret['errCode']);
echo json_encode($ret);
?>