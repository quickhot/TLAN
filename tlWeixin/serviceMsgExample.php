<?php
include_once 'classes/ServiceMsg.class.php';
include_once 'conf/weixin.php';
$accessToken = ACCESS_TOKEN;
echo "ACCESS_TOKEN:".ACCESS_TOKEN."<br />"."<br />";
$openId = "oMIYity2u2hEmi-qYTVqp7n96RSI";
$serviceMsg = new ServiceMsg($accessToken);
$resp = $serviceMsg->textMsg($openId, "中文消息");
echo $resp."<br />";
$mediaId="2_DvnvXcpA8FS42Xb9XO1nYR2K5t5HQAIxKTiqZoRAiIEaN_a7hTIWLSdPKci3Eu";
$resp = $serviceMsg->imgMsg($openId, $mediaId);
echo $resp."<br />";

$voiceMediaId='xkXlHjgsuQdzHt8AZblHphm5GE9UxYIRbInW3WXOshi-noB8kJQyDnsFeVWMxpVr';
$resp = $serviceMsg->voiceMsg($openId, $voiceMediaId);
echo $resp."<br />";

$videoMediaId='-r3aP6P5N1DN-3Qg0I64aXClSEU3F0o6K0nmXm3Xb75hy0CgDlcVEQTTCRm99fAL';
$thumbMediaId='rZ2cpAJc8DhpMVA6AG5bDw7h6fF_1VnBRyJ-oNz0FQRHXI_UIp91QhoNKvvrpUNs';
$title='Avideo';
$desc='Thedesc';
$resp = $serviceMsg->videoMsg($openId, $videoMediaId,$thumbMediaId,$title,$desc);
echo $resp."<br />";

$thumbMediaId='rZ2cpAJc8DhpMVA6AG5bDw7h6fF_1VnBRyJ-oNz0FQRHXI_UIp91QhoNKvvrpUNs';
$title='A Music';
$desc='Music Desc';
$musicUrl = "http://wx.quickhot.com/music/low.mp3";
$hqMusicUrl = "http://wx.quickhot.com/music/high.mp3";
$resp = $serviceMsg->musicMsg($openId, $musicUrl, $hqMusicUrl, $thumbMediaId,$title,$desc);
echo $resp."<br />";

$serviceMsg->addOneNews('头条', '头条新闻', 'http://news.qq.com', 'https://res.wx.qq.com/mpres/zh_CN/htmledition/comm_htmledition/style/xss/page/page_service_package_intro_z.png');
$serviceMsg->addOneNews('二条', '二条新闻', 'http://news.baidu.com', 'http://mat1.gtimg.com/www/images/qq2012/qqlogo_2x.png');
$resp = $serviceMsg->newsMsg($openId);
echo $resp."<br />";
