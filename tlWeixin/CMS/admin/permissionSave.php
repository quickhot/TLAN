<?php
	include '../config.inc.php';
	include '../class/lhcDAO.php';
	include_once '../lib/loginStatus.php';
	
	if($_SESSION['level'] == 1){
		$adminId = $_GET['adminId'];
		$saveParam = explode(",", $_GET['saveParam']);
		$lhcDAO = new lhcDAO();
		$result = $lhcDAO -> permissionSave($db, $adminId, $saveParam);
		if ($result) {
			echo "<script>alert('操作成功');window.location.href='permissionManage.php?adminId=".$adminId."'</script>";
		}
		//print_r($saveParam);
		//echo $adminId;
	}else{
		echo "无权分配";
	}
?>