<?php
header ( 'Content-type: text/json' );
$selectPro=$_POST['selectPro'];
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


	$conn = new MysqlDB(DBHOST,DBUSER,DBPASS,DBNAME);
	$link = $conn->link;
	mysql_query("SET AUTOCOMMIT=0",$link);
	mysql_query("begin",$link);
	//删除已经提交的报数
	$delIfExist = "DELETE dailycountoff.* FROM dailycountoff
	     INNER JOIN staff AS oneStaff ON oneStaff.`id` = dailycountoff.`staffId`
	     INNER JOIN staff AS twoStaff ON twoStaff.`outletId` = oneStaff.`outletId`
	     WHERE twoStaff.`openId`='$openId' AND DATE(dailycountoff.`countOffDate`)=CURDATE()";
    $resDelIfExist = mysql_query($delIfExist,$link);
    if ($resDelIfExist) {

        //插入dailyCountOff
    	$insDaily = "INSERT INTO dailycountoff(countOffDate,staffId) (SELECT NOW(),id FROM staff WHERE openId='$openId')";
    	mysql_query($insDaily,$link);
    	$dailyId = mysql_insert_id($link);
    	if ($dailyId) {
    		foreach ($proList as $productId => $amount) {
    			$sqlValues = $sqlValues."($productId,$amount,$dailyId),";
    		}
    		$insDetail = substr("INSERT INTO countoffdetail(productId,amount,countOffId) VALUES $sqlValues",0,-1);
    		if (mysql_query($insDetail,$link)) {
    			$ret['success']=1;
    			$ret['errCode'] = 1;
    		} else {
    			$ret['errCode'] = -23;
    		}
    	} else $ret['errCode'] = -24;
    } else $ret['errCode'] = -22;

	if ($ret['errCode']>0) {
		mysql_query("commit",$link);
	} else mysql_query("rollback",$link);
	mysql_query("set autocommit=1",$link);


$errInfo = new ErrInfo();
$ret['errInfo'] = $errInfo->getErrInfoByCode($ret['errCode']);
//$ret['errInfo'] = $insDetail;
echo json_encode($ret);
?>