<?php
header ( 'Content-type: text/json' );
$selectPro=$_POST['selectPro'];
$nearPic=$_POST['nearPic'];
$acceptPic=$_POST['acceptPic'];
$farPic=$_POST['farPic'];
$openId = $_POST['openId'];

$ret = array('success'=>0,'errCode'=>1);

$proList =array();
$numA = array();
$idA = array();
$regNum = '/^\d+/';
$regId = '/(?<=\[\*)\d+/';
foreach ($selectPro as $value) {
	preg_match($regNum, $value,$numA);
	preg_match($regId,$value,$idA);
	$proList["$idA[0]"]=$numA[0];
}

include_once '../conf/mysql.php';
include_once '../classes/MysqlDB.class.php';
include_once '../classes/ErrInfo.class.php';
include_once '../classes/General.class.php';

$docRoot = $_SERVER["DOCUMENT_ROOT"].'/';
$movNearPic = copy($docRoot.'uploadTemp/'.$nearPic, $docRoot.'photos/'.$nearPic);
$movAcceptPic = copy($docRoot.'uploadTemp/'.$acceptPic, $docRoot.'photos/'.$acceptPic);
$movFarPic = copy($docRoot.'uploadTemp/'.$farPic, $docRoot.'photos/'.$farPic);

if ($movAcceptPic && $movNearPic && $movFarPic) {
	$conn = new MysqlDB(DBHOST,DBUSER,DBPASS,DBNAME);
	$link = $conn->link;
	mysql_query("SET AUTOCOMMIT=0",$link);
	mysql_query("begin",$link);
	$insAccept = "INSERT INTO acceptance(accTime,staffId,acceptDocPic,acceptGoodsPic,acceptNearPic) (SELECT NOW(),id,'$acceptPic','$farPic','$nearPic' FROM staff WHERE openId='$openId')";
	mysql_query($insAccept,$link);
	$acceptId = mysql_insert_id($link);
	if ($acceptId) {
		
		foreach ($proList as $productId => $amount) {
			$sqlValues = $sqlValues."($productId,$amount,$acceptId),";
		}
		$insDetail = substr("INSERT INTO acceptdetail(productId,amount,acceptanceId) VALUES $sqlValues",0,-1);
		if (mysql_query($insDetail,$link)) {
			$ret['success']=1;
			$ret['errCode'] = 1;
		} else {
			$ret['errCode'] = -15;
		}
	} else $ret['errCode'] = -14;
	
	if ($ret['errCode']>0) {
		mysql_query("commit",$link);
		unlink($docRoot.'uploadTemp/'.$nearPic);
		unlink($docRoot.'uploadTemp/'.$acceptPic);
		unlink($docRoot.'uploadTemp/'.$farPic);
	} else mysql_query("rollback",$link);
	mysql_query("set autocommit=1",$link);
	
} else {
	$ret['errCode']= -13;
}

$errInfo = new ErrInfo();
$ret['errInfo'] = $errInfo->getErrInfoByCode($ret['errCode']);
//$ret['errInfo'] = $insDetail;
echo json_encode($ret);
?>