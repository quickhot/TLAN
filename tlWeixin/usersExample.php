<?php
include_once 'conf/weixin.php';
include_once 'classes/Users.class.php';

$accessToken = ACCESS_TOKEN;
$user = new Users($accessToken);

$openId='oMIYit4vcSrbGnpKGWJVD8QqvFlA';
$userDetail = $user->getUserDetailByOpenId($openId);
var_dump($userDetail);

$openIds = $user->getOpenIds();
var_dump($openIds);

$redirectUrl = $user->oauthRedirect("http://wx.quickhot.com/oauth.php","snsapi_userinfo","someStates");
echo "<a href='$redirectUrl'>gogo!</a>";