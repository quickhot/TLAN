<?php
//echo $_REQUEST['echostr'];
include_once 'classes/Response.class.php';
//调用logger
include_once 'classes/log4php/Logger.php';
Logger::configure('conf/log4php.xml');
$logger=Logger::getLogger("myAppender");

include_once 'classes/Analyze.class.php';

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
$mediaId = $postArray['MediaId'];
$postContent = $postArray['Content'];
$thumbMediaId = $postArray['ThumbMediaId'];
$event = $postArray['Event'];
$eventKey = $postArray['EventKey'];
 
//加载微信配置
include_once 'conf/weixin.php';

//加载媒体类
//include_once 'classes/Media.class.php';
//$media = new Media(ACCESS_TOKEN);

//上传文件
//$mediaResultJson=$media->upLoadMedia('/var/wx.quickhot.com/images/1.jpg', 'image');
//$mediaResult = json_decode($mediaResultJson);
//$mediaIdResult = $mediaResult->media_id;

//下载文件
//$header = $media->getMediaInfo($mediaId);//获取文件头信息
//$oriFileName = $media->getFileNameByHeader($header);//获取原始文件名
//$logger->info($oriFileName);
//include_once 'classes/General.class.php';
//$gen = new General();
//$ext = $gen->getFileExtension($oriFileName);//获取扩展名
//$logger->info($ext);
//$documentRoot = __DIR__;
//$saveFile = $documentRoot."/media/".time().'.'.$ext;
//$logger->info($saveFile);
//$media->downloadMedia($mediaId, $saveFile);//抓取文件并保存

//通过response类发送消息
$response=new Response();
//$xml = $response->textMsg($fromUserName, $toUserName, $event."***".$eventKey);
//$xml = $response->picMsg($fromUserName, $toUserName, $mediaId);
//$xml = $response->voiceMsg($fromUserName, $toUserName, $mediaId);
//$xml = $response->videoMsg($fromUserName, $toUserName, $mediaId,$thumbMediaId);
//$xml = $response->musicMsg($fromUserName, $toUserName,null,null,null,null,$mediaId);

$response->addOneNews('hello', 'this is a test news', 'http://wx.bigit.cn/yixianli/ggl2/images/fengmian.jpg', "http://wx.quickhot.com/testNews.php");
//$response->addOneNews('hello', 'this is a test news', 'http://wx.bigit.cn/yixianli/ggl2/images/fengmian.jpg', "http://wx.bigit.cn/yixianli/ggl2/index.php?d=oDNf6jqVkUAl2zU9Ier6WtPgO2Wc&k=d41d8cd98f00b204e9800998ecf8427e");
//$response->addOneNews('hello', 'this is a test news', 'http://wx.bigit.cn/yixianli/ggl2/images/fengmian.jpg', "http://wx.bigit.cn/yixianli/ggl2/index.php?d=oDNf6jqVkUAl2zU9Ier6WtPgO2Wc&k=d41d8cd98f00b204e9800998ecf8427e");
//$logger->info($response->newsContent);
$xml1=$response->newsMsg($fromUserName, $toUserName);
sleep(1);
$xml2=$response->newsMsg($fromUserName, $toUserName);
sleep(1);
$xml3=$response->newsMsg($fromUserName, $toUserName);
$xml = $xml1.$xml2.$xml3;
$logger->info($xml);

$response->sendXml($xml);


// include_once 'classes/ServiceMsg.class.php';
// include_once 'conf/weixin.php';
// $openId = $fromUserName;
// $accessToken = ACCESS_TOKEN;
// $serviceMsg = new ServiceMsg($accessToken);
// $resp = $serviceMsg->textMsg($openId, "中文消息");
// $serviceMsg->addOneNews('头条', '头条新闻', 'http://news.qq.com', 'https://res.wx.qq.com/mpres/zh_CN/htmledition/comm_htmledition/style/xss/page/page_service_package_intro_z.png');
// //$serviceMsg->addOneNews('二条', '二条新闻', 'http://news.baidu.com', 'http://mat1.gtimg.com/www/images/qq2012/qqlogo_2x.png');
// $resp = $serviceMsg->newsMsg($openId);
// $resp = $serviceMsg->newsMsg($openId);

$logger->info("***********************************************");
?>