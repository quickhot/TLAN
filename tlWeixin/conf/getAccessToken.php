<?php
//替换文件中的内容
function edit($file, $accessToken){
	$content = file_get_contents($file);
	if(!$content){
		return false;
	}
	$accessToken = "define(ACCESS_TOKEN,'$accessToken');";
	$content = preg_replace('/\bdefine\b\(ACCESS_TOKEN.{2,152}\'\)\;/', $accessToken, $content);
	return file_put_contents($file, $content) ? true : false;
}
//获取配置文件
include_once 'weixin.php';
$appId = appID;
$secret = appsecret;
//获取accessToken的json串
$content=file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appId&secret=$secret");
$realContent = json_decode($content);
//拿到了accessToken
$accessToken = $realContent->access_token;
$documentRoot=__DIR__;
edit($documentRoot."/weixin.php",$accessToken);
?>