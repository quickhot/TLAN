<?php
include_once '../lib/loginStatus.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>品牌管理</title>
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
<script src="../js/jquery.jqGrid.js" type="text/javascript"></script>
<script src="../js/jquery.tablednd.js" type="text/javascript"></script>
<script src="../js/jquery.contextmenu.js" type="text/javascript"></script>
<script src="../js/ui.multiselect.js" type="text/javascript"></script>
</head>
<body style="background: #626262;">
<div class="rightl">
<p class="word" style="color: #e9be2a">*请您认真品牌名称，以确保数据完整，删除品牌将删除所有关于此品牌的数据，不建议删除</p>
<table id="rowed1"></table>
<div id="prowed1"></div>
<br />

<script src="brand.js" type="text/javascript"> </script>

</div>
</body>
</html>