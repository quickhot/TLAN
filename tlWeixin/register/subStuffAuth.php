<?php
$verifyCode=$_POST['verifyCode'];
$phoneNo=$_POST['phoneNo'];
$idCard=$_POST['idCard'];
$wxId = $_POST['wxIds'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no" />
<title>员工认证</title>
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
</script>
</head>
<body>
<?php
include_once '../conf/mysql.php';
include_once '../classes/General.class.php';
include_once '../classes/MysqlDB.class.php';
include_once '../classes/ErrInfo.class.php';
$openId = General::wldecode ( urldecode ( $wxId ) );
$newConn = new MysqlDB(DBHOST, DBUSER, DBPASS, DBNAME);
$retCode = $newConn->doRegist($verifyCode,$phoneNo,$idCard,$openId);
$errInfo = new ErrInfo();
$retMsg = $errInfo->getErrInfoByCode($retCode);
?>
<div data-role="page" id="dialog">
  <div data-role="header" data-theme="b">
    <h1><?php if ($retCode>0) {
    	echo "注册成功";
    	} else{
		echo "注册失败";
		}
		 ?></h1>
  </div>
  <div data-role="content">
    <?php
    if ($retCode>0) {
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
<?php  
    } else {
		echo "注册失败，请您重试～！<br />".$retMsg;
	}
	?>
  </div>
  
  <div data-role="footer" data-theme="b">
    <h1><button id="closePage" name="closePage" >关闭</button></h1>
  </div>
</div>

</body>