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

$(function(){
	$("#addList").click(function(){
		$("#listV").append('<li><h2>品牌名</h2><a style="text-align: center" class="delClick">货品名<span class="ui-li-count">25</span></a></li>');
		$("#listV").listview( "refresh" );
	});

	$("#listV").on("click","li",function(){
		alert($(this).find("a").text());
		alert($(this).parent().find("a").text());
		//$(this).css("background-color","red");
		//$(this).parent().parent().parent().remove();
		$(this).remove();
		$("#listV").listview( "refresh" );
	});
	
	function getSelectVal(){ 
	    $.getJSON("getProducts.php",{brand:$("#brand").val()},function(json){ //从getProduct.php拿数据
	        var product = $("#product"); 
	        $("option",product).remove(); //清空原有的选项，可以用option.empty();代替 
	        $.each(json,function(index,array){ 
	            var option = "<option value='"+array['ProductID']+"'>"+array['ProductName']+"</option>"; 
	            product.append(option); 
	        }); 
	    }); 
	} 
			
});


</script>

</head>
<body>
	
	<div data-role="page" id="accept">
	<div data-theme="b" data-role="header">
        <h3>
            验收单
        </h3>
    </div>
    <div data-role="content">
    <div data-role="fieldcontain" data-controltype="selectmenu">
            <label for="selectmenu1">
                品牌：
            </label>
            <select id="brand" name="brand" data-theme="b">
            	
<?php foreach ($brands as $key => $value) {
 echo "<option value=\"".$key."\">".$value."</option>"; 
}?>
            </select>
        </div>
        <div data-role="fieldcontain" data-controltype="selectmenu">
            <label for="selectmenu2">
                品名：
            </label>
            <select id="selectmenu2" name="" data-theme="b">
                <option value="option1">
                    Option 1
                </option>
            </select>
        </div>
    
		
			<button id="addList" name="addList">添加一个</button>
			<h4>收货单</h4>
			<ul id="listV" data-role="listview" data-inset="true" data-icon="delete">
				<li><h2>标题1</h2><a style="text-align: center" class="delClick">收件箱<span	class="ui-li-count">25</span></a></li>
				<li><h2>标题2</h2><a style="text-align: center" class="delClick">收件箱<span class="ui-li-count">25</span></a></li>
				<li><a class="delClick">发件箱<span class="ui-li-count">432</span></a></li>
				<li><a class="delClick">垃圾箱<span class="ui-li-count">7</span></a></li>
			</ul>
			
			
			<div data-role="fieldcontain" data-controltype="camerainput">
            <label for="camerainput1">
                近景照片：
            </label>
            <input type="file" name="" id="camerainput1" accept="image/*" capture="camera"
            data-mini="true">
        </div>
        <div data-role="fieldcontain" data-controltype="camerainput">
            <label for="camerainput2">
                验收单照片：
            </label>
            <input type="file" name="" id="camerainput2" accept="image/*" capture="camera"
            data-mini="true">
        </div>
        <div data-role="fieldcontain" data-controltype="camerainput">
            <label for="camerainput3">
                整体照片：
            </label>
            <input type="file" name="" id="camerainput3" accept="image/*" capture="camera"
            data-mini="true">
        </div>
		<button>提交验收单</button>	
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

