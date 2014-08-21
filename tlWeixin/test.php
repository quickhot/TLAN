<?php

?>
<html>
	<head>
	  <meta charset="utf-8"/>
	  <title></title>
	  <script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	  <script	src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
	  <script src="../js/WeixinApi.js"></script>
	  <script type="text/javascript" charset="utf-8">
	  
	  WeixinApi.ready(function(Api){
    // 隐藏右上角popup菜单入口
    	Api.hideOptionMenu();
     // 隐藏浏览器下方的工具栏
    	Api.hideToolbar();
    	
    	function closePage(){
    		Api.closeWindow();
    	}
    	
    	$("#closePage").click(
			function(){
				Api.closeWindow();
			}
   		);
	});
	  
	    $(function(){
	    	function CloseWin()
	    	{
	    		window.open('about:blank','_self');
	    		window.close();
	    	}   
	    	
	    	$("#close").on("click",function(){
	    		alert();
	    		WeixinJSBridge.call("closeWindow");
	    	});
	    });
	  </script>
	</head>
	<meta charset="UTF-8"/>
	<body>
		<button id="close">close</button>
	</body>
</html>