<?php
header ( 'Content-type: text/json' );

$invoicePic=$_POST['invoicePic'];
$barCodePic=$_POST['barCodePic'];
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
$movBarCodePic = copy($docRoot.'uploadTemp/'.$barCodePic, $docRoot.'photos/'.$barCodePic);
$movInvoicePic = copy($docRoot.'uploadTemp/'.$invoicePic, $docRoot.'photos/'.$invoicePic);

if ($movBarCodePic && $movNearPic && $movFarPic && $movInvoicePic) {
	$conn = new MysqlDB(DBHOST,DBUSER,DBPASS,DBNAME);
	$link = $conn->link;
	mysql_query("SET AUTOCOMMIT=0",$link);
	mysql_query("begin",$link);
	$insExhibits = "INSERT INTO exhibits(exTime,staffId,barCodePic,nearPic,invoicePic,farPic) (SELECT NOW(),id,'$barCodePic','$nearPic','$invoicePic','$farPic' FROM staff WHERE openId='$openId')";
	mysql_query($insExhibits,$link);
	$exhibitsId = mysql_insert_id($link);
	if ($exhibitsId) {

	} else $ret['errCode'] = -21;
	
	if ($ret['errCode']>0) {
		mysql_query("commit",$link);
		unlink($docRoot.'uploadTemp/'.$nearPic);
		unlink($docRoot.'uploadTemp/'.$barCodePic);
		unlink($docRoot.'uploadTemp/'.$farPic);
		unlink($docRoot.'uploadTemp/'.$invoicePic);
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