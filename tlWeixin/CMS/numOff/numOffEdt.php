<?php
header ( 'Content-type: text/json' );
include '../dbInc.php';
include '../class/tsDAO.php';
include_once '../lib/loginStatus.php';

$tsDAO = new tsDAO($dbHost,$dbUser,$dbPass,$dbname,$dbPort);
if ($tsDAO -> numOffEdit($_POST)) {
    $res['success']=1;
} else $res['success']=0;

echo json_encode($res);
?>