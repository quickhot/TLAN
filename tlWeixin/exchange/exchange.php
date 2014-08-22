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
<title>退换货/买赠</title>
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

/*
	//添加货品按钮点击
	$("#addList").click(function(){
		var brandText=$("#brand").find("option:selected").text();
		var productText=$("#product").find("option:selected").text();
		var productId = $("#product").val();
		var amount = $("#amount").val();
		if (amount=='undefined' || amount==''){
			alert("请填写货品数量");
			return false;
		}
		$("#listV").append('<li><h2>'+brandText+'</h2><a style="text-align: center" class="delClick">[*'+productId+'*]'+productText+'<span class="ui-li-count">'+amount+'</span></a></li>');
		$("#listV").listview( "refresh" );
	});

	//点击已经添加的货品列表，标识要删除
	$("#listV").on("click","li",function(){
		if(confirm("您确定要删除"+$(this).find("a").text()+"吗？")) {
		//$(this).css("background-color","red");
		//$(this).parent().parent().parent().remove();
		$(this).remove();
		$("#listV").listview( "refresh" );
		}
	});
*/
	//当品牌选择框发生变化时，获取产品的函数
	function getSelectVal(){ 
	    $.getJSON("../getProducts.php",{brand:$("#brand").val()},function(json){ //从getProduct.php拿数据
	        var product = $("#product"); 
	        $("option",product).remove(); //清空原有的选项，可以用option.empty();代替 
	        $.each(json,function(index,array){ 
	            var option = "<option value='"+array['ProductID']+"'>"+array['ProductName']+"</option>"; 
	            product.append(option); 
	        });
	        $("#product").selectmenu("refresh",true);
	    }); 
	}

	//点击上传文件按钮
	$("[type='file']").live("change",function(){
		//上传文件开始显示对话框
		$(this).ajaxStart(function(){
			$.mobile.changePage("#beginUpload",{role:"dialog"});
		})
		//上传文件结束返回
		$(this).ajaxComplete(function(){
			$.mobile.changePage("#accept");
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
	function submitAccept (selectPro,nearPic,acceptPic,farPic) {
		$.ajax({
			type: "post",
			url: "submitAccept.php",
			data:{'selectPro':selectPro,'nearPic':nearPic,'acceptPic':acceptPic,'farPic':farPic,'openId':'<?php echo $openId;?>'},
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
	$("#subAccept").click(function(){
		var selectPro = new Array();
		$("#listV li").each(function(n,value) {
			selectPro.push($(this).find("span").text()+"!!!"+$(this).find("a").text());
		});
		var nearPic = $("#nearPic").val();
		var acceptPic = $("#acceptPic").val();
		var farPic = $("#farPic").val();
		var vCode = checkVerifyCode();
		if (vCode=="1"){
			if (selectPro.length!=0 && nearPic!='' && acceptPic!='' && farPic !='') {
				submitAccept(selectPro,nearPic,acceptPic,farPic);
			} else{
				alert("信息不完整，请填写完全");
			}
		} else alert("验证码不正确");
	});
	//开始运行时获取产品列表
	getSelectVal(); 
	//品牌列表变化，触发事件
    $("#brand").change(function(){ 
        getSelectVal(); 
    }); 
});  
			
</script>

</head>
<body>
	
	<div data-role="page" id="accept" data-theme="b">
	<div data-theme="b" data-role="header">
        <h3>退换货/买赠</h3>
    </div>
    <div data-role="content">
    <div data-role="fieldcontain" data-controltype="selectmenu">
            <label for="brand">
                品牌：
            </label>
            <select id="brand" name="brand" data-theme="b">
            	
<?php foreach ($brands as $key => $value) {
 echo "<option value=\"".$key."\">".$value."</option>"; 
}?>
            </select>
        </div>
        <div data-role="fieldcontain" data-controltype="selectmenu">
            <label for="product">
                品名：
            </label>
            <select id="product" name="" data-theme="b">

            </select>
        </div>
    <div data-role="fieldcontain" data-controltype="textinput">
            <label for="amount">
                货品数量
            </label>
            <input name="amount" id="amount" placeholder="请填写货品数量..." type="number" />
        </div>
		
<!--
	 		<button id="addList" name="addList">添加货品</button>
			<h4 style="text-align:center">收货清单</h4>
			<ul id="listV" data-role="listview" data-inset="true" data-icon="delete">
			</ul>
 -->			
			
			<div data-role="fieldcontain" data-controltype="camerainput">
            <label for="nearPhoto">近景照片：</label>
            <input type="file" name="nearPhoto" id="nearPhoto" accept="image/*" capture="camera" data-mini="true">
            <label for="nearPic" class="ui-hidden-accessible">nearPic</label>
            <input name="nearPic" id="nearPic" type="hidden" />
        </div>
        <div data-role="fieldcontain" data-controltype="camerainput">
            <label for="acceptDoc">货架照片：</label>
            <input type="file" name="acceptDoc" id="acceptDoc" accept="image/*" capture="camera" data-mini="true">
            <label for="acceptPic" class="ui-hidden-accessible">acceptPic</label>
            <input name="acceptPic" id="acceptPic" type="hidden" />
        </div>
        <div data-role="fieldcontain" data-controltype="camerainput">
            <label for="farPhoto">整体照片：</label>
            <input type="file" name="farPhoto" id="farPhoto" accept="image/*" capture="camera" data-mini="true">
            <label for="farPic" class="ui-hidden-accessible">farPic</label>
            <input name="farPic" id="farPic" type="hidden" />
        </div>
        <button id="getCodeButton" type="button" data-theme="b">获取验证码</button>
        
        <div data-role="fieldcontain" data-controltype="textinput">
            <label for="verifyCode">
                验证码
            </label>
            <input name="verifyCode" id="verifyCode" placeholder="填写短信验证码..." value="" type="text">
        </div>
        
		<button id="subAccept">提交退换货/买赠</button>	
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

