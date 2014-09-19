<?php 
	include '../config.inc.php';
	include '../class/lhcDAO.php';
	include_once '../lib/loginStatus.php';
	//echo $_GET['adminId'];
	$adminId = $_GET['adminId'];
	$lhcDAO = new lhcDAO();
	$adminLevel = $_SESSION['level'];
	//echo $adminLevel;//普通管理0
	if($adminLevel == 1){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>客户信息管理</title>
<link rel="stylesheet" type="text/css" media="screen" href="../themes/redmond/jquery-ui-custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../themes/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../themes/ui.multiselect.css" />

<link href="../css/global.css" type="text/css" rel="stylesheet"/>
<link href="../css/right.css" type="text/css" rel="stylesheet"/>

<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/jquery-ui-custom.min.js" type="text/javascript"></script>
<script src="../js/jquery.layout.js" type="text/javascript"></script>
<script src="../js/i18n/grid.locale-cn.js" type="text/javascript"></script>
<script type="text/javascript">
	$.jgrid.no_legacy_api = true;
	$.jgrid.useJSON = true;
</script>
<script src="../js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="../js/jquery.tablednd.js" type="text/javascript"></script>
<script src="../js/jquery.contextmenu.js" type="text/javascript"></script>
<script src="../js/ui.multiselect.js" type="text/javascript"></script>
</head>
<body style="background: #626262;">
<div class="rightl">
<!-- <p class="word"> 小区用户管理：管理小区内的房屋和对应的业主信息， 业主手机号码是唯一标识，必须填写。</p> -->

<table id="rowed1"></table>
<div id="prowed1"></div>
<br />
<!-- 
<input type="BUTTON" id="ed1" value="Edit row 2" />
<input type="BUTTON" id="sved1" disabled='true' value="Save row 2" />
<input type="BUTTON" id="cned1" disabled='true' value="Cancel Save" />
<input type="BUTTON" id="edtrow" value="Edit selected row" />
 -->
<input id="adminId" type="hidden" value="<?php echo $adminId?>"/>
<input type="button" id="saveButton" value="保存" />
<script src="permissionManage.js" type="text/javascript"> </script>

</div>
</body>
</html>
<?php
	}else{
		echo "无法分配权限";
	}
?>