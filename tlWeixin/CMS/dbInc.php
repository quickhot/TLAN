<?php
$serverAddr = $_SERVER["SERVER_ADDR"];
if (substr($serverAddr, 0,3)=='192') {
    $dbHost='121.199.49.230';
    $dbPort=9306;
} else {
    $dbHost='10.140.185.163';
    $dbPort=3306;
}

$dbUser='weixin';
$dbPass='weixin_123789';
$dbname='weixin';
