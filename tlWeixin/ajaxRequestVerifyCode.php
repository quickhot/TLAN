<?php
header ( 'Content-type: text/json' );
$wxId = $_POST ['wxId'];
$phoneNo = $_POST ['phoneNo'];
$idCard = $_POST['idCard'];
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
if ($openId && $phoneNo) {
	$newConn = new MysqlDB ( DBHOST, DBUSER, DBPASS, DBNAME );
	if ($newConn) {
		$checkPhoneNo = $newConn->checkPhoneNo($phoneNo,$idCard);
		if($checkPhoneNo<0){
			$ret['success']=0;
			$ret['errCode']=$checkPhoneNo;
		} else {
			$getVerifyCode = $newConn->requestVerifyCode ( $openId );
			if ($getVerifyCode<0) {
				$ret['success']=0;
				$ret['errCode']=-6;
			} else {
				$sendSMS = General::sendSMS($phoneNo,"您的短信验证码是：$getVerifyCode");
				if ($sendSMS==1) {
					$ret['success']=1;
					$ret['errCode']=1;
				} else {
					$ret['success']=0;
					$ret['errCode']=$sendSMS;
				}
				
			}
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