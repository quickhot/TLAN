<?php 
$wxId = $_GET['wxId'];
$errCode=1;
if (!$wxId) {
	$errCode = -3;
} else {
	include_once '../conf/mysql.php';
	include_once '../classes/MysqlDB.class.php';
	include_once '../classes/ErrInfo.class.php';
	include_once '../classes/General.class.php';
	
	$openId = General::wldecode($wxId);
	
	$newConn = new MysqlDB(DBHOST, DBUSER, DBPASS, DBNAME);
	$resCheck = $newConn->checkRegist($openId);
	if ($resCheck<0) {
		$errCode = $resCheck;
	} else $staffId = $resCheck;
}
if ($errCode < 0) {
	$errInfo = new ErrInfo();
	$errMsg = $errInfo->getErrInfoByCode($errCode);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no" />
<title>货物验收</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
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

var errCode = <?php echo $errCode;?>;
$(function() {
	if (errCode < 0) {
		$.mobile.changePage("#dialog");
	}
});

</script>

</head>
<body>
<div data-role="page" data-control-title="员工认证" id="stuffAuth">
    <div data-theme="b" data-role="header">
        <h3>
            员工认证
        </h3>
    </div>
    <div data-role="content">
    <form id="postAuth" method="post" action="subStuffAuth.php" data-ajax="false">
    	<div data-role="fieldcontain" data-controltype="textinput">
            <label for="wxIds" class="ui-hidden-accessible">
                wxIds
            </label>
            <input name="wxIds" id="wxIds" type="hidden" value="<?php echo $wxId;?>"/>
        </div>
        <div data-role="fieldcontain" data-controltype="textinput">
            <label for="phoneNo">
                手机号码
            </label>
            <input name="phoneNo" id="phoneNo" placeholder="请填写手机号码..." type="tel" />
        </div>
        
        <div data-role="fieldcontain" data-controltype="textinput">
            <label for="idCard">
                身份证号码
            </label>
            <input name="idCard" id="idCard" placeholder="请填写身份证号..." type="text" />
        </div>
        
        <button id="getCodeButton" type="button" data-theme="b">获取验证码</button>
        
        <div data-role="fieldcontain" data-controltype="textinput">
            <label for="verifyCode">
                验证码
            </label>
            <input name="verifyCode" id="verifyCode" placeholder="填写短信验证码..." value="" type="text">
        </div>
        <button id="subCode" name="subCode" data-theme="b" disabled="true">进行认证</button>
    </form>
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
        <p style="text-align: center"><button id="closePage">关闭</button></p>
    </div>
</div>
</body>
</html>

