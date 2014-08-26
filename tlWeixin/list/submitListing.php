<?php
header ( 'Content-type: text/json' );
$productId=$_POST['productId'];
$nearPic=$_POST['nearPic'];
$barCodePic=$_POST['barCode'];
$farPic=$_POST['farPic'];
$openId = $_POST['openId'];

$ret = array('success'=>0,'errCode'=>1);

include_once '../conf/mysql.php';
include_once '../classes/MysqlDB.class.php';
include_once '../classes/ErrInfo.class.php';
include_once '../classes/General.class.php';

$docRoot = $_SERVER["DOCUMENT_ROOT"].'/';
$movNearPic = copy($docRoot.'uploadTemp/'.$nearPic, $docRoot.'photos/'.$nearPic);
$movBarCodePic = copy($docRoot.'uploadTemp/'.$barCodePic, $docRoot.'photos/'.$barCodePic);
$movFarPic = copy($docRoot.'uploadTemp/'.$farPic, $docRoot.'photos/'.$farPic);

if ($movBarCodePic && $movNearPic && $movFarPic) {
	$conn = new MysqlDB(DBHOST,DBUSER,DBPASS,DBNAME);
	$link = $conn->link;
	mysql_query("SET AUTOCOMMIT=0",$link);
	mysql_query("begin",$link);
	$insAccept = "INSERT INTO listing(listTime,staffId,listNearPic,barCodePic,listFarPic,productId) (SELECT NOW(),id,'$nearPic','$farPic','$barCodePic','$productId' FROM staff WHERE openId='$openId')";
	mysql_query($insAccept,$link);
	$acceptId = mysql_insert_id($link);
	if ($acceptId) {

	} else $ret['errCode'] = -19;
	
	if ($ret['errCode']>0) {
		mysql_query("commit",$link);
		unlink($docRoot.'uploadTemp/'.$nearPic);
		unlink($docRoot.'uploadTemp/'.$barCodePic);
		unlink($docRoot.'uploadTemp/'.$farPic);
	} else mysql_query("rollback",$link);
	mysql_query("set autocommit=1",$link);
	
} else {
	$ret['errCode']= -13;
}

$errInfo = new ErrInfo();
$ret['errInfo'] = $errInfo->getErrInfoByCode($ret['errCode']);
//$ret['errInfo'] = $insAccept;
echo json_encode($ret);
?>