<?php
/*
23:30
货品上架：
3张照片
1、整体货架照片
2、近景货品照片
3、条码照片（超市标签）
*/
/*29:00
 * 销毁
 * 单子照片
 * 近景照片（瓶子日期）
 * 销毁中照片（整体）
 * 瓶盖照片（瓶数和瓶盖）
  
 * 
 * 买赠
 * 单子照片
 * 近景（日期要清晰）
 * 货架照片（整体的捆绑好的照片）
 * 仓库照片
 */
/*
 * 34:10陈列
 * 发票照片
 * 近景陈列货品保质期
 * 远景陈列员货架照片
 * 条码照片（超市标签）
 */
/*
 * 36:13报数
 * 
 */


//echo $_REQUEST['echostr'];
//调用logger
include_once 'classes/log4php/Logger.php';
Logger::configure('conf/log4php.xml');
$logger=Logger::getLogger("myAppender");

include_once 'classes/General.class.php';
//加载微信配置
include_once 'conf/weixin.php';

//微信接收消息分析类
include_once 'classes/Analyze.class.php';
//微信回复消息类
include_once 'classes/Response.class.php';

//获取微信发过来的消息
$postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
$logger->info($postStr);

//通过Analyze类，返回微信传过来的数组
$postAnalyze = new Analyze();
$postArray = $postAnalyze->convertXml($postStr);
$logger->info($postArray);

//获取数据
$fromUserName = $postArray['FromUserName'];
$toUserName = $postArray['ToUserName'];
//$mediaId = $postArray['MediaId'];
//$postContent = $postArray['Content'];
//$thumbMediaId = $postArray['ThumbMediaId'];
//$event = $postArray['Event'];
//$eventKey = $postArray['EventKey'];

$response = new Response();

if ($postArray['MsgType']=='event') {
	//认证
	if ($postArray['EventKey']=='register') {
		$regUrl = "http://".HOST."/register/staffAuth.php?wxId=".urlencode(General::wlencode($fromUserName));
		$echoStr = '<a href="'.$regUrl.'">点击此处员工认证</a>';
		$xml=$response->textMsg($fromUserName, $toUserName, $echoStr);
	};
	//验收单
	if ($postArray['EventKey']=='checkRecv') {
		$acceptUrl = "http://".HOST."/acceptance/accept.php?wxId=".urlencode(General::wlencode($fromUserName));
		$echoStr = '<a href="'.$acceptUrl.'">点击此处验收货物</a>';
		$xml=$response->textMsg($fromUserName, $toUserName, $echoStr);
	};
	//货品上架
	if ($postArray['EventKey']=='listing') {
		$listUrl = "http://".HOST."/list/list.php?wxId=".urlencode(General::wlencode($fromUserName));
		$echoStr = '<a href="'.$listUrl.'">点击此处货物上架</a>';
		$xml=$response->textMsg($fromUserName, $toUserName, $echoStr);
	};
	//退换货exchange
	if ($postArray['EventKey']=='exchange') {
		$exchangeUrl = "http://".HOST."/exchange/exchange.php?wxId=".urlencode(General::wlencode($fromUserName));
		$echoStr = '<a href="'.$exchangeUrl.'">点击此处退换/买赠</a>';
		$xml=$response->textMsg($fromUserName, $toUserName, $echoStr);
	};
	//陈列show
	if ($postArray['EventKey']=='show') {
		$exhibits = "http://".HOST."/exhibits/exhibits.php?wxId=".urlencode(General::wlencode($fromUserName));
		$echoStr = '<a href="'.$exhibits.'">点击此处陈列货品</a>';
		$xml=$response->textMsg($fromUserName, $toUserName, $echoStr);
	};
	
	if ($postArray['Event']=='subscribe') {
		$ret=file_get_contents("http://".HOST."/regNewUser.php?wxId=".urlencode(General::wlencode($fromUserName)));
		if ($ret>0) {
			$addNewUserReturn = "关注同瞬流程服务成功";
		} else $addNewUserReturn = "流程服务初始失败，请取消关注后，重新关注。";
		$xml=$response->textMsg($fromUserName, $toUserName, $addNewUserReturn);
	};
}

//$xml=$response->textMsg($fromUserName, $toUserName, json_encode($postArray));

$response->sendXml($xml);

?>

