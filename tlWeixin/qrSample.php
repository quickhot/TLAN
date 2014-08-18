<?php
include_once 'conf/weixin.php';
include_once 'classes/QRCode.class.php';

$qrCode = new QRCode(ACCESS_TOKEN);
$resp = $qrCode->createQRCode(123,90);
echo "<pre>";
var_dump($resp);
echo "</pre>";
$imgUrl = $qrCode->getQRImgUrlByTicket($resp['ticket']);
echo "<img src='$imgUrl' />";