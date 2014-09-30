<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>用户管理</title>
<link href="../css/global.css" type="text/css" rel="stylesheet"/>
<link href="../css/right.css" type="text/css" rel="stylesheet"/>
</head>
<body style="background: #626262;">
<div class="right">
<p class="word" style="color: #e9be2a">配置管理员信息</p>
<div class="tablelist" style="width:764px">
<?php
include_once '../dbInc.php';
include_once '../class/tsDAO.php';
include_once '../lib/loginStatus.php';
$thisUrl = $_SERVER['PHP_SELF'];
$do		=$_GET['do'];
$user_id	=$_GET['user_id'];
$iflogin=2;
//表名
$table='admin';
if ($do=='logout') {
    session_start();
    //使用tsSession会话变量检查登录状态
    if(isset($_SESSION['tsSession'])){
		    //要清除会话变量，将$_SESSION超级全局变量设置为一个空数组
		    $_SESSION = array();
		    //如果存在一个会话cookie，通过将到期时间设置为之前1个小时从而将其删除
		    if(isset($_COOKIE[session_name()])){
		    	setcookie(session_name(),'',time()-3600);
		    }
		    session_destroy();
    }
    echo "<script language='javascript' type='text/javascript'>window.open('../login.php','_parent');</script>";
    exit();
}

if(isset($tsSession)&&($_SESSION['level']==1)){

	//$notifyId	= $_GET['notifyId'];
	//$userPhone	= $_GET['userPhone'];
	//编辑数据
	if ($do=='edit') {
      //获取编辑后的 用户名、密码、等级放入数组
		$editarray['adminName']=trim($_POST['user']);
		$editarray['adminPass']=md5(trim($_POST['pass']));
		$editarray['level']=$_POST['level'];
		//条件数组
		$editcond['id']=$user_id;
		//创建tsDAO对象
		$tsDAO_edit=new tsDAO($dbHost,$dbUser,$dbPass,$dbname,$dbPort);
		//调用tsDA中的updateAdministrator方法
		$admin_update=$tsDAO_edit->updateOper($table,$editarray, $editcond);
		echo "<p class=\"word\" style=\"color: #fb565a\">";
		if ($admin_update) echo "修改成功"; else echo "修改失败";
		echo "</p>";
	}
	//删除操作
	if ($do=='delete') {
		//创建tsDAO对象
		$tsDAO_check = new tsDAO($dbHost,$dbUser,$dbPass,$dbname,$dbPort);
		$userLevel=$tsDAO_check->getLevelById($user_id);
		echo "<p class=\"word\" style=\"color: #fb565a\">";
		if (strval($userLevel)==1) {
			echo "不能删除超级管理员";
		} else {
		$admin_delete=$tsDAO_check->deleteOper($table,array("id"=>$user_id));
		//调用tsDA中的updateAdministrator方法
		if ($admin_delete) echo "删除成功"; else echo "删除失败";
		}
		echo "</p>";
	}
	//添加操作
	if ($do=='add'){
//获取添加后的 用户名、密码、等级放入数组
		$addarray['adminName']=trim($_POST['user']);
		$addarray['adminPass']=md5(trim($_POST['pass']));
		//$addarray['level']=$_POST['level']; //因为不允许添加超级管理员，所以这里直接就改成0了。
		$addarray['level']=0;
		echo "<p class=\"word\" style=\"color: #fb565a\">";
		if($addarray['adminName']!=null&&$addarray['adminPass']!=null){
			//创建tsDAO对象并调用getAdministratorByUsername检查用户名是否存在
			$tsDAO_add_text=new tsDAO($dbHost,$dbUser,$dbPass,$dbname,$dbPort);
			$add_text=$tsDAO_add_text->getAdministratorByUsername($addarray['adminName']);
			if(count($add_text)){//用户名已存在
				echo "用户名已存在，请重新添加</p>";
			}else{//可以创建用户
				//创建tsDAO对象并调用addAdministrator添加用户
				$admin_add=$tsDAO_add_text->insertOper($table,$addarray);
				if ($admin_add) echo "添加成功"; else echo "添加失败";
			}

		}else {
			echo "用户名或密码不可为空，请重新添加";
		}
		echo "</p>";
	}
	?>

	<div class="tit titbar titcor"><span>配置管理员信息</span></div>
	<table class="table1" cellpadding="0" cellspacing="0" border="1" style="width:764px">
				<tr>
			    <td align="center" bgcolor="#FFFFFF">用户名</td>
			     <td align="center" bgcolor="#FFFFFF">密码</td>
			     <td align="center" bgcolor="#FFFFFF">等级</td>
			    <td align="center" bgcolor="#FFFFFF">动作</td>
			  </tr>
	<?php
	//创建tsDAO_query对象
	$tsDAO_query=new tsDAO($dbHost,$dbUser,$dbPass,$dbname,$dbPort);
	//调用tsDAO_query对象的getAdministrator方法
	$admin_query=$tsDAO_query->getAdministrator();
	//foreach循环页面显示数据
    foreach ($admin_query as $arr){
        $userid=$arr['id'];
    	$username=$arr['adminName'];//获取当前行的中用户名
    	$password=$arr['adminPass'];//获取当前行的中用户名
		$level_No=$arr['level'];  //获取当前行的等级
		//判断等级并输出相对应的中文名称

		//初始化两个变量用来标记是否被选中
		$general='';
		$super='';
		if($level_No==0){
			$level_str="普通管理员";
			$general='selected';
		}elseif ($level_No==1){
			$level_str="超级管理员";
			$super='selected';
		}
		//页面中需要改那一行数据
		if ($do=='modify'&&$userid==$user_id){
			?>

		<form enctype="multipart/form-data" method="post" action="<?php echo $thisUrl;?>?do=edit&user_id=<?php echo $userid;?>">
		  <tr>
		    <td bgcolor="#FFFFFF"><input type="text" name="user" value="<?php echo $username;?>" /></td>
		    <td bgcolor="#FFFFFF"><input type="password" name="pass" value="" /></td>
			<td bgcolor="#FFFFFF">
			     <select name="level" size = "1" >
			     <?php  if($level_No == 0) {
						echo "<option value=0  selected=\"$general\">普通管理员</option>";
			     } else {
						echo("<option value=1 >超级管理员</option>");
				}
			     	?>

<?php #echo("<option value=1 >超级管理员</option>");  ?>
			     </select>
		     </td>
			<td align="center" bgcolor="#FFFFFF"><input type="submit" value="提交" name="submit"><input type="button" value="取消" name="cancel" onClick="history.back();" /></td>
		  </tr>
		</form>
		<?php }else  { //遍历普通的数据 	?>
		  <tr>
		     <td bgcolor="#FFFFFF"   style="display:none;"><?php echo $userid;?></td>
		     <td bgcolor="#FFFFFF"><?php echo $username;?></td>
		     <td bgcolor="#FFFFFF">******</td>
			 <td bgcolor="#FFFFFF"><?php echo $level_str;?></td>
			 <td align="center" bgcolor="#FFFFFF"><a href="<?php echo $thisUrl;?>?do=modify&user_id=<?php echo $userid;?>">修改</a><?php if($level_No == 0){?> | <a href="<?php echo $thisUrl;?>?do=delete&user_id=<?php echo $userid;?>">删除</a> | <a href='permissionManage.php?adminId=<?php echo $userid;?>'>权限分配</a><?php }?></td>
		  </tr>
		<?php
		}
    }
	//页面中进行添加一行数据
	if($do=='new'){
		?>
		<form enctype="multipart/form-data" method="post" action="<?php echo $thisUrl;?>?do=add">
		<tr>
		    <td bgcolor="#FFFFFF"><input type="text" name="user" /></td>
			<td bgcolor="#FFFFFF"><input type="text" name="pass" /></td>
			<td bgcolor="#FFFFFF">
			 <select name="level" size = "1" >
					<option value=0 >普通管理员</option>
<?php #echo("<option value=1 >超级管理员</option>");  ?>
			</select>
		    </td>
			<td align="center" bgcolor="#FFFFFF"><input type="submit" value="提交" name="submit"><input type="button" value="取消" name="cancel" onClick="history.back();" /></td>
		</tr>
		</form>
		  <?php
	} else echo "<tr><td colspan='4' align='right'><a href='$thisUrl?do=new'>添加一行</a></td></tr>";
	?>
	</table>

	<?php
} else {
	echo "<script language='javascript' type='text/javascript'>";
	echo "alert('为了安全，请您登录');";
	echo "window.open('../login.php','_parent');";
	echo "</script>";
}
?>
</div>
</div>
</body>
</html>