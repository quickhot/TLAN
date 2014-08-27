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
<title>货品陈列</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script	src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<script src="../js/WeixinApi.js"></script>
<script type="text/javascript" src="../js/ajaxfileupload.js" ></script>
<script type="text/javascript">

var wait = 60;
function time(o) {
	if (wait == 0) {
		o.button("enable");
		o.html("再次获取验证码").button("refresh");
		wait = 60;
	} else {
		wait--;
		newText = wait + "秒后重新发送";
		o.html(newText).button("refresh");
        o.button("disable");
		setTimeout(function() {
			time(o)
		}, 1000);
	}
}
//获取验证码
function requestVerifyCode(wxId)
{
	$.ajax({
		type: "post",
		url: "../getVCode.php",
		data:{'wxId':wxId},
		beforeSend: function(XMLHttpRequest){
		},
		success: function(data, textStatus){
		if(textStatus == 'success'){
			if(data != ''){
				if (data.success == '1'){
					time($("#getCodeButton"));
	 		 		return true;
				} else {
					alert(data.errInfo);
					return false;
				}
			 }
		}else{
			alert('加载失败请重试');
			return false;
			}
		},
		complete: function(XMLHttpRequest, textStatus){}
	});
}
//校验验证码
function checkVerifyCode(){
	var vCode = $("#verifyCode").val();
	var results ="0";
	$.ajax({
		type: "post",
		async:false,
		url: "../checkVCode.php",
		data:{'wxId':'<?php echo $wxId;?>','vCode':vCode},
		beforeSend: function(XMLHttpRequest){
		},
		success: function(data, textStatus){
		if(textStatus == 'success'){
			if(data != ''){
				if (data.success == '1'){
	 		 		results = "1";
				} else {
					//alert(data.errInfo);
				}
			 }
		}else{
			alert('加载失败请重试');
			return false;
			}
		},
		complete: function(XMLHttpRequest, textStatus){}
	});
	return results;
}

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
	
	//获取短信验证码
	$("#getCodeButton").click(function()
         {
			requestVerifyCode("<?php echo $wxId;?>");
         }
    );


	//点击上传文件按钮
	$("[type='file']").live("change",function(){
		//上传文件开始显示对话框
		$(this).ajaxStart(function(){
			$.mobile.changePage("#beginUpload",{role:"dialog"});
		})
		//上传文件结束返回
		$(this).ajaxComplete(function(){
			$.mobile.changePage("#exhibits");
		});
		//上传文件
		var title = $(this).parent().prev();
		var ids = $(this).attr("id");
		$.ajaxFileUpload
		(
			{
				url:'../doajaxfileupload.php?flag='+Math.random(),
				secureuri:false,
				fileElementId:ids,
				dataType: 'json',
				data:{field:ids},
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);
						}else
						{
							$(title).nextAll("input").val(data.msg);
							$(title).text($(title).text()+"(已经上传)");
							$(title).parent().css("background","green");
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)
	});
	//点击最终上传按钮函数
	function submitExhibits(invoicePic,nearPic,farPic,barCodePic) {
		$.ajax({
			type: "post",
			url: "submitExhibits.php",
			data:{
				'invoicePic':invoicePic,
				'farPic':farPic,
				'nearPic':nearPic,
				'barCodePic':barCodePic,
				'openId':'<?php echo $openId;?>'},
			beforeSend: function(XMLHttpRequest){
			},
			success: function(data, textStatus){
			if(textStatus == 'success'){
				if(data != ''){
					if (data.success == '1'){
						alert(data.errInfo);
						WeixinJSBridge.call("closeWindow");
		 		 		return true;
					} else {
						alert(data.errInfo);
						return false;
					}
				 }
			}else{
				alert('加载失败请重试');
				return false;
				}
			},
			complete: function(XMLHttpRequest, textStatus){}
		});
	}
	
	//点击最终上传按钮触发事件
	$("#subExhibits").click(function(){
		var invoicePic = $("#invoicePic").val();
		var nearPic = $("#nearPic").val();
		var farPic = $("#farPic").val();
		var barCodePic = $("#barCodePic").val();
		var vCode = checkVerifyCode();
		if (vCode=="1"){
			if (invoicePic!='' && nearPic!='' && farPic!='' && barCodePic !='') {
				submitExhibits(invoicePic,nearPic,farPic,barCodePic);
			} else{
				alert("信息不完整，请填写完全");
			}
		} else alert("验证码不正确");
	});

});  
			
</script>

</head>
<body>
	
	<div data-role="page" id="exhibits" data-theme="b">
	<div data-theme="b" data-role="header">
        <h3>货品陈列</h3>
    </div>
    <div data-role="content">
    
		<div data-role="fieldcontain" data-controltype="camerainput">
            <label for="invoicePhoto">发票照片：</label>
            <input type="file" name="invoicePhoto" id="invoicePhoto" accept="image/*" capture="camera" data-mini="true">
            <label for="invoicePic" class="ui-hidden-accessible">invoicePic</label>
            <input name="invoicePic" id="invoicePic" type="hidden" />
        </div>			
		<div data-role="fieldcontain" data-controltype="camerainput">
            <label for="nearPhoto">货品保质期照片：</label>
            <input type="file" name="nearPhoto" id="nearPhoto" accept="image/*" capture="camera" data-mini="true">
            <label for="nearPic" class="ui-hidden-accessible">nearPic</label>
            <input name="nearPic" id="nearPic" type="hidden" />
        </div>
        <div data-role="fieldcontain" data-controltype="camerainput">
            <label for="farPhoto" id="destory">陈列员货架照片：</label>
            <input type="file" name="farPhoto" id="farPhoto" accept="image/*" capture="camera" data-mini="true">
            <label for="farPic" class="ui-hidden-accessible">farPic</label>
            <input name="farPic" id="farPic" type="hidden" />
        </div>
        <div data-role="fieldcontain" data-controltype="camerainput">
            <label for="barCodePhoto" id="cap">条码照片（超市标签）：</label>
            <input type="file" name="barCodePhoto" id="barCodePhoto" accept="image/*" capture="camera" data-mini="true">
            <label for="barCodePic" class="ui-hidden-accessible">barCodePic</label>
            <input name="barCodePic" id="barCodePic" type="hidden" />
        </div>
                
        <button id="getCodeButton" type="button" data-theme="b">获取验证码</button>
        
        <div data-role="fieldcontain" data-controltype="textinput">
            <label for="verifyCode">
                验证码
            </label>
            <input name="verifyCode" id="verifyCode" placeholder="填写短信验证码..." value="" type="text">
        </div>
        
		<button id="subExhibits">提交陈列货品</button>	
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



<div data-role="page" data-theme="b" id="beginUpload" data-close-btn="none">
	
		<div data-role="header">
			<h1>上传图片</h1>
		</div>
		<div data-role="content">
			<h1>图片上传中</h1>
			<p>请等待图片上传，上传完毕后，此框自动消失...</p>
		</div>
</div>

</body>
</html>

