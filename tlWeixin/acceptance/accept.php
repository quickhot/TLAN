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
<title>货物验收</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script	src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<script src="../js/WeixinApi.js"></script>
<script type="text/javascript" src="../js/ajaxfileupload.js" ></script>
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
	if (errCode < 0) {
		$.mobile.changePage("#dialog");
	}
	
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

	$("#listV").on("click","li",function(){
		if(confirm("您确定要删除"+$(this).find("a").text()+"吗？")) {
		//$(this).css("background-color","red");
		//$(this).parent().parent().parent().remove();
		$(this).remove();
		$("#listV").listview( "refresh" );
		}
	});
	
	function getSelectVal(){ 
	    $.getJSON("getProducts.php",{brand:$("#brand").val()},function(json){ //从getProduct.php拿数据
	        var product = $("#product"); 
	        $("option",product).remove(); //清空原有的选项，可以用option.empty();代替 
	        $.each(json,function(index,array){ 
	            var option = "<option value='"+array['ProductID']+"'>"+array['ProductName']+"</option>"; 
	            product.append(option); 
	        });
	        $("#product").selectmenu("refresh",true);
	    }); 
	}

	//上传文件开始显示对话框
	$("[type='file']").live("change",function(){
		$(this).ajaxStart(function(){
			$.mobile.changePage("#beginUpload",{role:"dialog"});
		})
		$(this).ajaxComplete(function(){
			$.mobile.changePage("#accept");
		});
		
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
	
	
	$("#subAccept").click(function(){
		var selectPro = new Array();
		$("#listV li").each(function(n,value) {
			selectPro.push($(this).find("span").text()+"!!!"+$(this).find("a").text());
		});
		
		var nearPic = $("#nearPic").val();
		var acceptPic = $("#acceptPic").val();
		var farPic = $("#farPic").val();
		if (selectPro.length!=0 && nearPic!='' && acceptPic!='' && farPic !='') {
			submitAccept(selectPro,nearPic,acceptPic,farPic);
		} else{
			alert("请填写完整信息");
		}
	});
	
	getSelectVal(); 
    $("#brand").change(function(){ 
        getSelectVal(); 
    }); 
});  
			
</script>

</head>
<body>
	
	<div data-role="page" id="accept" data-theme="b">
	<div data-theme="b" data-role="header">
        <h3>
            验收单
        </h3>
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
		
			<button id="addList" name="addList">添加货品</button>
			<h4>收货单</h4>
			<ul id="listV" data-role="listview" data-inset="true" data-icon="delete">
			</ul>
			
			
			<div data-role="fieldcontain" data-controltype="camerainput">
            <label for="nearPhoto">近景照片：</label>
            <input type="file" name="nearPhoto" id="nearPhoto" accept="image/*" capture="camera" data-mini="true">
            <label for="nearPic" class="ui-hidden-accessible">nearPic</label>
            <input name="nearPic" id="nearPic" type="hidden" />
        </div>
        <div data-role="fieldcontain" data-controltype="camerainput">
            <label for="acceptDoc">验收单照片：</label>
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
		<button id="subAccept">提交验收单</button>	
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

