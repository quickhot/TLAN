<!DOCTYPE PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript">
function sendUrl(URL){
	var myForm = document.createElement("form");   
	  myForm.method="post" ; 
	  myForm.action = URL ;     
    var myInput = document.createElement("input") ;
    	myInput.name = "menuId";
	    myInput.setAttribute("value", '4') ;   
	    myForm.appendChild(myInput) ; 
	  document.body.appendChild(myForm) ; 
	  myForm.submit();   
	  document.body.removeChild(myForm) ;
	  return true;
	}
</script>
</head>
<body>
<a href="#"  onclick="return sendUrl('test2.php?type=12');">click here!</a>
</body>
</html>
<?php
?>