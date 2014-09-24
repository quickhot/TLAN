<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>小秘书管理系统</title>
<link href="css/global.css" type="text/css" rel="stylesheet"/>
<link href="css/left.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="js/yahoo-dom-event.js"></script>
<script>
function sendUrl(URL,MS){
	var myForm = document.getElementById("sendMenuId");
	myForm.action = URL ;
    var myInput = document.getElementById("MS") ;
	myInput.value = MS ;
	myForm.submit();
	return true;
	}
</script>
<script language="javascript">
tempj=2;
function showed(tempi){
	if(document.getElementById("show"+tempj.toString()).style.display==''&&tempi!=tempj)
	{
	   document.getElementById("show"+tempj.toString()).style.display='none';
	}
	if(document.getElementById("show"+tempi.toString()).style.display=='none')
	{
	   document.getElementById("show"+tempi.toString()).style.display='';
	}
	else
	{
	   document.getElementById("show"+tempi.toString()).style.display='none';
	}
  tempj=tempi;
}
</script>

<base target="mainFrame" />
</head>
<body style="background: #626262;">
<form name='sendMenuId' id="sendMenuId" method="post">
<input type="hidden" id="MS" name="MS" />
</form>
<div class="left" id="main-menu">
<div class="listcon" id="yc-menu">

<?php
include_once 'dbInc.php';
include_once 'lib/loginStatus.php';
require_once 'class/tsDAO.php';

//$sessionId = session_id();
//基本变量设置
$GLOBALS["ID"] =1; //用来跟踪下拉菜单的ID号
$layer=1; //用来跟踪当前菜单的级数

//创建对象并获取一级菜单
$tsDAO=new tsDAO($dbHost,$dbUser,$dbPass,$dbname,$dbPort);
$result=$tsDAO->getAmenu();

//判断管理员等级，显示相应菜单
if($_SESSION['level'] == 0){
	//如果一级菜单存在则开始菜单的显示
	if(count($result)>0) ShowTreeMenu($result,$layer,$tsDAO);
}elseif($_SESSION['level'] == 1){
	echo "<div class=\"top_menu\"><a href='admin/manager.php'>管理员管理</a></div><div class=\"solidline\"></div>";
}


//=============================================
//显示树型菜单函数 ShowTreeMenu($result,$layer)
// $result：需要显示的菜单数组
// $layer：需要显示的菜单的级数
//=============================================
function ShowTreeMenu($result,$layer,$dao)
{
	//取得需要显示的菜单的项目数
	$numrows=count($result);

	//开始显示菜单，每个子菜单都用一个表格来表示
	//echo "<table cellpadding='0' cellspacing='5' border='0'>";
	for($rows=0;$rows<$numrows;$rows++)
	{
			//将当前菜单项目的内容导入数组
			$menu=$result[$rows];
			//创建对象并提取菜单项目的子菜单记录集
			$result_sub=$dao->getSubmenu($menu['id']);
			//echo "<tr>";
			//子菜单的数量
			$submenu_num=count($result_sub);
			//判断是主菜单还是子菜单
			if($menu['parent_id']==0){
                //如果该菜单项目有子菜单，则添加JavaScript onClick语句
                 if($submenu_num>0)
				{
					echo "<dt class=\"top_menu\" onclick=\"javascript:showed(".$GLOBALS["ID"].");\">";
				}
				else
				{  //没有子栏目
				echo "<dt class=\"top_menu\">";
				}

			}
			//是否是一级菜单
			if($menu['parent_id']==0){
                //如果该菜单项目没有子菜单，并指定了超级连接地址，则指定为超级连接，
				//否则只显示菜单名称
				if($menu['url']!="") {
					echo "<a href='javascript:void(0)' onclick='return sendUrl(\"".$menu['url']."\",\"".$menu['id']."\");'><div class=\"menu_font\">".$menu['name']."</div></a>";
				}else{
                    echo  $menu['name'];
// 					echo "<div class=\"menu_font\">".$menu['name']."</div><div class=\"down_icon\"><img src=\"/images/down.png\" /></div>";
				}
		     	echo "</dt>";
	         }else{
	         	//如果该菜单项目没有子菜单，并指定了超级连接地址，则指定为超级连接，
				//否则只显示菜单名称
				if($menu['url']!="") {
					echo "<a href='javascript:void(0)' onclick='return sendUrl(\"".$menu['url']."\",\"".$menu['id']."\");'><dd>>&nbsp;".$menu['name']."</dd></a>";
				}else{
					echo ">&nbsp;".$menu['name'];
				}
	         }
	         echo "<div class=\"solidline\"></div>";
// 			//如果该菜单项目有子菜单，则显示子菜单
			if($submenu_num>0)
			{
				//指定该子菜单的ID和style，以便和onClick语句相对应
				echo "<div class=\"sub_meuu\" id=\"show".$GLOBALS["ID"]++."\" style=\"display:none;\">";
				//将级数加1
				$layer++;
				//递归调用ShowTreeMenu()函数，生成子菜单
				ShowTreeMenu($result_sub,$layer,$dao);
				//子菜单处理完成，返回到递归的上一层，将级数减1
				$layer--;
				echo "</div>";
			}
	     //继续显示下一个菜单项目
	}
 }

 echo "<div class=\"top_menu\"><a  href='admin/manager.php?do=logout'>退出登录</a></div>";

?>
</div>
</div>
<script type="text/javascript">
var $D  = YAHOO.util.Dom;
var $E  = YAHOO.util.Event;
var con = $D.get("yc-menu").getElementsByTagName("dd");
$E.on(con,'click',function(e){
 var el = $E.getTarget(e);
 if(!el)return;
 for(var i = 0;i<con.length;i++){
  $D.removeClass(con[i],'click_color');
 }
 $D.addClass(el,'click_color');
});

var $mainD  = YAHOO.util.Dom;
var $mainE  = YAHOO.util.Event;
var maincon = $mainD.get("main-menu").getElementsByTagName("dt");
$mainE.on(maincon,'click',function(e){
 var mainel = $mainE.getTarget(e);
 if(!mainel)return;
 for(var i = 0;i<maincon.length;i++){
  $mainD.removeClass(maincon[i],'main_color');
 }
 $mainD.addClass(mainel,'main_color');
});
</script>
</body>
</html>