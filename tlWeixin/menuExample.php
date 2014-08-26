<?php 
//include_once 'conf/weixin.php';
include_once 'classes/Menu.class.php';
//沽上江南，保存现有的菜单
$accessToken = 'mafxjHUUGtpT2Xn5XsXCjPRQuyPQOrd_6InjEP2W6UX62GTQX092fjdU5fdQ7MYF2vBzkNFER_7jx3fOGWO6vw';
$menu = new Menu($accessToken);
$existMenu = $menu->getExistMenu();//获取现在正在使用的menu数组
//$existMenu =json_decode(file_get_contents('menuJson.txt'),true);
echo "<pre>";
var_dump($existMenu);
echo "</pre>";
//$menu->setMenu($existMenu);//把获取到的menu数组设置进去
//$menu->addMenu("view", "第三个菜单", "www.qq.com", 3, null); //添加一个一级菜单
//$menu->addMenu("click", "世界杯竞猜", "20140613",2, null);//添加一个二级菜单
//$menu->addMenu("click", "第三子菜单", "hello", 3, 5);//添加一个二级菜单
$currentMenu = $menu->getMenu();
echo "<pre>";
var_dump($currentMenu);
echo "</pre>";

$jsonMenu = json_encode($currentMenu);
echo $jsonMenu;
echo "success?".file_put_contents('GSJN_menuJson.txt', $jsonMenu);
// echo "<pre>";
// var_dump(json_decode(urldecode(file_get_contents('menuJson.txt')),true));
// echo "</pre>";
$delResponse = $menu->deleteMenu();
//删除菜单
echo "DELETE MENU:".$delResponse."<br />";
//创建菜单
//$response=$menu->createMenu();
echo $response."<br />";
?>