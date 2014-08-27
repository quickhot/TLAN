<?php
header ( 'Content-type: text/json' );

$cType	=$_POST['cType'];
$amount	=$_POST['amount'];
$docPic	=$_POST['docPic'];
$inventoryPic=$_POST['inventoryPic'];
$productId=$_POST['productId'];
$nearPic=$_POST['nearPic'];
$farPic=$_POST['farPic'];
$openId = $_POST['openId'];

$ret = array('success'=>0,'errCode'=>1);

include_once '../conf/mysql.php';
include_once '../classes/MysqlDB.class.php';
include_once '../classes/ErrInfo.class.php';
include_once '../classes/General.class.php';

$docRoot = $_SERVER["DOCUMENT_ROOT"].'/';
$movNearPic = copy($docRoot.'uploadTemp/'.$nearPic, $docRoot.'photos/'.$nearPic);
$movFarPic = copy($docRoot.'uploadTemp/'.$farPic, $docRoot.'photos/'.$farPic);
$movDocPic = copy($docRoot.'uploadTemp/'.$docPic, $docRoot.'photos/'.$docPic);
$movInventoryPic = copy($docRoot.'uploadTemp/'.$inventoryPic, $docRoot.'photos/'.$inventoryPic);

if ($movDocPic && $movNearPic && $movFarPic && $movInventoryPic) {
	$conn = new MysqlDB(DBHOST,DBUSER,DBPASS,DBNAME);
	$link = $conn->link;
	mysql_query("SET AUTOCOMMIT=0",$link);
	mysql_query("begin",$link);
	$insRCG = "INSERT INTO returnchangegrant(RCGDatetime,staffId,docPic,nearPic,inventoryPic,farPic,productId,amount) (SELECT NOW(),id,'$docPic','$nearPic','$inventoryPic','$farPic','$productId','$amount' FROM staff WHERE openId='$openId')";
	mysql_query($insRCG,$link);
	$RCGId = mysql_insert_id($link);
	if ($RCGId) {

	} else $ret['errCode'] = -20;
	
	if ($ret['errCode']>0) {
		mysql_query("commit",$link);
		unlink($docRoot.'uploadTemp/'.$nearPic);
		unlink($docRoot.'uploadTemp/'.$inventoryPic);
		unlink($docRoot.'uploadTemp/'.$docPic);
		unlink($docRoot.'uploadTemp/'.$farPic);
	} else mysql_query("rollback",$link);
	mysql_query("set autocommit=1",$link);
	
} else {
	$ret['errCode']= -13;
}

$errInfo = new ErrInfo();
$ret['errInfo'] = $errInfo->getErrInfoByCode($ret['errCode']);
//$ret['errInfo'] = json_encode($_POST);
echo json_encode($ret);
?>