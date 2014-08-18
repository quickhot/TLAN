<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no" />
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script	src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<script type="text/javascript">

$(function(){
	$("#addList").click(function(){
		$("#listV").append('<li><h2>品牌名</h2><a style="text-align: center" class="delClick">货品名<span class="ui-li-count">25</span></a></li>');
		$("#listV").listview( "refresh" );
	});
	
/*
	$(".delClick").on("click",function(){
		alert(this.text);
		//$(this).css("background-color","red");
		$(this).parent().parent().parent().remove();
		$("#listV").listview( "refresh" );
	});
*/
	$("#listV").on("click","li",function(){
		alert($(this).find("a").text());
		alert($(this).parent().find("a").text());
		//$(this).css("background-color","red");
		//$(this).parent().parent().parent().remove();
		$(this).remove();
		$("#listV").listview( "refresh" );
	});
	
		
});

</script>
</head>
<body>

	<div data-role="page" id="pageone">
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
            <select id="selectmenu1" name="" data-theme="b">
                <option value="option1">
                    Option 1
                </option>
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

</body>
</html>

