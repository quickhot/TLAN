<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理员登录</title>
<link rel="stylesheet" type="text/css" href="css/Xiaoms_index.css"/>
<link rel="shortcut icon" href="images/zhiye.ico" />
</head>
<body>
<div id="container">
<?php
include_once 'dbInc.php';
include_once 'class/tsDAO.php';
$thisUrl = $_SERVER['PHP_SELF'];
$do=$_GET['do'];
//记录登陆状态   0未登录 1已登陆
$iflogin=3;

if($do=='login'){
	//获取用户名和密码
	$username=$_POST['userName'];
	$password=$_POST['password'];
	//创建tsDAO对象
	$tsDAO=new tsDAO($dbHost,$dbUser,$dbPass,$dbname,$dbPort);
	if (!$tsDAO) {
	    echo "数据库连接失败";
	    exit();
	}
	//调用方法返回数据
	$tsLogin =$tsDAO->getAdminIdAndLevel($username, $password);
//	var_dump($tsLogin);
	$adminId=$tsLogin[0];
	if($adminId){
	        session_start();
	        $_SESSION['tsSession']="logined";
	        $_SESSION['loginName']=$username;
	        $_SESSION['adminId']=$adminId;
	        $_SESSION['level']=$tsLogin[1];
	        $accessArr = $tsDAO->getAccess($adminId);
	        for($i=0;$i<count($accessArr);$i++){
	        	$newArr[$i] = $accessArr[$i][0];
	        }
	        $_SESSION['access'] = $newArr;
			$iflogin=1;
	}else $iflogin=0;
}
?>
<form method="post" action="<?php echo $thisUrl?>?do=login">
   <div id="center">
       <div id="cycle">
         <div id="cycle_logo">
              <div id="logo_container">
                <div id="logo">
		         </div>
		      </div>
		   </div>
        </div>
           <div id="form">
	              <div id="top">
                   <div class="user">
                    <input type="text" name="userName"  class="text"  />
				   </div>
                     <div class="pwd">
                     <input type="password" name="password" class="text" />
                    </div>
			      </div>

		           <div id="bottom">
                     <div id="forget">
					 <h1><a href="#" title="">忘记密码</a></h1>
                     </div>
                      <div class="login">
				     <input type="submit" class="submit" title="登录" value="" />
                     </div>
                   </div>
                 <div id="line" style="color: lightpink"><?php
	if ($iflogin==1) {
		echo "<script language='javascript' type='text/javascript'>window.location.href='index.php'</script>";

	} else if($iflogin==0){
		echo "<p align='center'>用户名和密码错，请检查。</p>";
	}
?></div>
         </div>
   </div>
</form>

</div>
</body>
</html>