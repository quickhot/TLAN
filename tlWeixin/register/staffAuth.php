<?php 
$wxId = $_GET['wxId'];
if (!$wxId) {
	//TODO: CHANGE THE STATUS 
	//$wxId = "UxNjYxYTJiOWVmZ2hfYm";
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no" />
<title>员工认证</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script	src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>

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

function requestVerifyCode(wxId,phoneNo,idCard)
{
	$.ajax({
		type: "post",
		url: "../ajaxRequestVerifyCode.php",
		data:{'wxId':wxId,'phoneNo':phoneNo,'idCard':idCard},
		beforeSend: function(XMLHttpRequest){
		},
		success: function(data, textStatus){
		if(textStatus == 'success'){
			if(data != ''){
				if (data.success == '1'){
					$("#phoneNo").attr('readOnly','true');
					$("#idCard").attr('readOnly','true');
					$("#subCode").button("enable");
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

$(function() {
    $("#getCodeButton").click(function()
         {
			if ($("#phoneNo").val() != '' &&  $("#idCard").val() != '' ){
				requestVerifyCode("<?php echo $wxId;?>",$("#phoneNo").val(),$("#idCard").val());
			} else {
				alert('手机号和身份证号必须填写');
			}
			//return false;
         }
    );
    
    $("#subCode").click(function(){
    	  if ($("#verifyCode").val()!=''){
    	  	$("#postAuth").submit();
    	  }else{
    	  	alert("请输入验证码");
    	  	return false;
    	  }
    	}
    );
});
</script>
</head>
<body>
<div data-role="page" data-control-title="员工认证" id="staffAuth">
    <div data-theme="b" data-role="header">
        <h3>
            员工认证
        </h3>
    </div>
    <div data-role="content">
    <form id="postAuth" method="post" action="subStaffAuth.php" data-ajax="false">
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
</body>

</html>

