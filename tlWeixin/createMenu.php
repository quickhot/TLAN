<?php 
include_once 'conf/weixin.php';
include_once 'classes/Menu.class.php';
$accessToken = ACCESS_TOKEN;
$menu = new Menu($accessToken);
$existMenu = $menu->getExistMenu();//获取现在正在使用的menu数组
echo "<pre>";
var_dump($existMenu);
echo "</pre>";
$menu->setMenu($existMenu);//把获取到的menu数组设置进去
$menu->addMenu("click", "认证", "register", 1, null); //添加一个一级菜单
$menu->addMenu("click", "工作", "jobs", 2, null);
$menu->addMenu("click", "员工", "staff", 3, null);

$menu->addMenu("click", "验   收   单", "checkRecv", 2, 1);
$menu->addMenu("click", "货品  上架", "listing", 2, 2);
$menu->addMenu("click", "退换/买赠", "exchange", 2, 3);
$menu->addMenu("click", "每日  报数", "staff", 2, 4);

$menu->addMenu("view", "员工手册", "http://mp.weixin.qq.com/s?__biz=MzA4Mzg4MzYyNQ==&mid=200490315&idx=1&sn=4f039bca80aedf2313302e9698ff39e3#rd", 3, 1);
$menu->addMenu("click", "陈列费", "showFee", 3, 2);
$menu->addMenu("click", "账号信息", "accountDetail", 3, 3);

$delResponse = $menu->deleteMenu();
//删除菜单
echo "DELETE MENU:".$delResponse."<br />";
//创建菜单
$response=$menu->createMenu();
echo $response."<br />";
?>