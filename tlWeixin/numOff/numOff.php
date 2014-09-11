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
<title>每日报数</title>
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
	//当有错误的时候，就是错误码为负数
	if (errCode < 0) {
		$.mobile.changePage("#dialog");
	}


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

	//点击最终上传按钮函数
	function submitNumOff (selectPro) {
		$.ajax({
			type: "post",
			url: "submitNumOff.php",
			data:{'selectPro':selectPro,'openId':'<?php echo $openId;?>'},
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
	$("#subNumOff").click(function(){
		var selectPro = new Array();
		$("#listV li").each(function(n,value) {
			selectPro.push($(this).find("span").text()+"!!!"+$(this).find("a").text());
		});

	    if (selectPro.length!=0) {
			submitNumOff(selectPro);
		} else{
			alert("信息不完整，请填写完全");
		}

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

	<div data-role="page" id="numOff" data-theme="b">
	<div data-theme="b" data-role="header">
        <h3>
            每日报数
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
			<h4 style="text-align:center">收货清单</h4>
			<ul id="listV" data-role="listview" data-inset="true" data-icon="delete">
			</ul>
		<button id="subNumOff">提交报数单</button>
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
</html>

