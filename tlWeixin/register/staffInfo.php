<?php
$wxId = $_GET['wxId'];
$errCode=1;
//如果没传送ID，那么错误码-3
if (!$wxId) {
	$errCode = -3;
} else {
	include_once '../conf/mysql.php';
	include_once '../classes/MysqlDB.class.php';
	include_once '../classes/ErrInfo.class.php';
	include_once '../classes/General.class.php';

	$openId = General::wldecode($wxId);

	$newConn = new MysqlDB(DBHOST, DBUSER, DBPASS, DBNAME);
	//检查是否注册员工
	$resCheck = $newConn->checkRegist($openId);
	if ($resCheck<0) {
		$errCode = $resCheck;
	} else
	{
		$staffId = $resCheck;
		$brands = $newConn->getBrands();
		if ($brands<0) {
			$errCode = $brands;
		} else {
			//do nothing
		}
	}
}
if ($errCode < 0) {
	$errInfo = new ErrInfo();
	$errMsg = $errInfo->getErrInfoByCode($errCode);}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no" />
<title>员工信息</title>
<link rel="stylesheet"
	href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script	src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<script src="../js/WeixinApi.js"></script>
<script type="text/javascript">
WeixinApi.ready(function(Api){
    // 隐藏右上角popup菜单入口
    Api.hideOptionMenu();
     // 隐藏浏览器下方的工具栏
    Api.hideToolbar();

    $("#closePage").click(
		function(){
			Api.closeWindow();
		}
   	);
});

$(function(){

	var errCode = <?php echo $errCode;?>;
	//当有错误的时候，就是错误码为负数
	if (errCode < 0) {
		$.mobile.changePage("#dialog");
	}
});
</script>
</head>
<body>

<div data-role="page" id="staffInfo">
  <div data-role="header" data-theme="b">
    <h1>员工信息</h1>
  </div>
  <div data-role="content">
<?php
		$userDetail=$newConn->getUserDetail($openId);
?>
     <h4 style="text-align:center">员工详细信息</h4>
    <ul data-role="listview" data-inset="true">
      <li>
        <h2>姓名：</h2>
        <p style="text-align:center"><?php echo $userDetail['staffName'];?></p>
      </li>
      <li>
        <h2>性别：</h2>
        <p style="text-align:center"><?php echo $userDetail['gender'];?></p>
      </li>
      <li>
      	<h2>身份证号码：</h2>
      	<p style="text-align:center"><?php echo $userDetail['idCard'];?></p>
      </li>
      <li>
      	<h2>手机号码：</h2>
      	<p style="text-align:center"><?php echo $userDetail['mobileNo'];?></p>
      </li>
      <li>
      	<h2>所属门店：</h2>
      	<p style="text-align:center"><?php echo $userDetail['outletName'];?></p>
      </li>
      <li>
      	<h2>门店地址：</h2>
      	<p style="text-align:center"><?php echo $userDetail['province'].'/'.$userDetail['city'].'/'.$userDetail['county'].'/'.$userDetail['address'];?></p>
      </li>
    </ul>
  </div>

  <div data-role="footer" data-theme="b">
    <div data-role="navbar">
    <ul>
        <li><button id="closePage">关闭</button></li>
    </ul>
    </div>
  </div>
</div>

<div data-role="page" data-control-title="dialog" id="dialog">
    <div data-theme="b" data-role="header">
        <h3>错误</h3>
    </div>
    <div data-role="content">
    <div data-controltype="textblock">
            <p>
                <span style="font-size: medium;">
                    <b>
                        <?php echo $errMsg;?>
                    </b>
                </span>
            </p>
        </div>
    </div>
    <div data-theme="b" data-role="footer" data-position="fixed">
        <div data-role="navbar">
    <ul>
        <li><button id="closePage">关闭</button></li>
    </ul>
    </div>
    </div>
</div>

</body>