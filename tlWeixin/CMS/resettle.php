<?php
mysql_connect ( '192.168.2.250', 'root', '123456' );
mysql_query ( 'SET NAMES utf8' );
//mysql_select_db ( $dbName);
$msg='';
mysql_query('BEGIN;');
$qryNotify = 'SELECT `LHC`.notify.* FROM `LHC`.notify ';
if (!($resNotify = mysql_query($qryNotify))) $msg=$msg.mysql_error();
$oldTitle='';
while ($rowNotify = mysql_fetch_array($resNotify,MYSQL_ASSOC)) {
	$oldNotifyId = $rowNotify['id'];
	$newTitle=$rowNotify['title'];
	$roomId = $rowNotify['roomId'];
	$newPubTime = $rowNotify['pubTime'];
	$content = $rowNotify['content'];
	$picture = $rowNotify['picture'];
	$URL = $rowNotify['URL'];
	$urgency = $rowNotify['urgency'];
	
	if (!($newTitle==$oldTitle AND $newPubTime==$oldPubTime)) {
		$insNotify = "INSERT INTO `LHCnew`.notify VALUES(NULL,'$newTitle','$newPubTime','$content','$picture','$URL',$urgency)";
		if (!mysql_query($insNotify)) $msg=$msg.mysql_error();
		$notifyId = mysql_insert_id();
	}
	$insNotifyAll="INSERT `LHCnew`.notifyAll VALUES(NULL,$notifyId,$roomId)";
	if (!mysql_query($insNotifyAll)) $msg=$msg.mysql_error();
	$notifyAllId=mysql_insert_id();
	
	$insNotifyStatus = "INSERT INTO `LHCnew`.notifyStatus SELECT NULL,$notifyAllId,userPhone,hasRead,hasDeleted FROM `LHC`.notifyStatus WHERE notifyId=$oldNotifyId";
	if (!mysql_query($insNotifyStatus)) $msg=$msg.mysql_error();
	
	$oldTitle = $newTitle;
	$oldPubTime = $newPubTime;
}
if ($msg=='') {
	mysql_query('COMMIT;');
	echo "success";
} else {
	echo "wrong";
	echo $msg;
	var_dump($msg);
	mysql_query('ROLLBACK;');
}
mysql_close();