<?php
 session_start();
 if (isset($_POST['MS'])) {
 	$ms=$_POST['MS'];
 	$_SESSION['ms']=$ms;
  } else $ms=$_SESSION['ms'];

   if(isset($_SESSION["tsSession"])){
   	    $tsSession=$_SESSION['tsSession'];//登陆状态
   	    $access = $_SESSION['access'];
		$userName = $_SESSION['loginName']; //登陆的用户名
		//echo "<script>alert(".$_POST['MS'].")</script>";
		//var_dump($_SESSION['access']);
		$thisURL=$_SERVER['PHP_SELF'];
		if (!(($thisURL=="/CMS/index.php")||($thisURL=="/CMS/left.php")||($thisURL=="/CMS/top.php")||((strpos($thisURL,"/CMS/admin/")!==false)))){
			in_array($ms,$access) or die("您没有访问此功能的权限！");
		}
	} else {
		echo "<script language='javascript' type='text/javascript'>";
		echo "alert('为了安全，请您登录');";
		echo "window.open('/CMS/login.php','_parent');";
		echo "</script>";
	}
?>