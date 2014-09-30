<?php
header ( 'Content-type: text/json' );

include '../dbInc.php';
include '../class/tsDAO.php';
include_once '../lib/loginStatus.php';

$res = array();
if ($_POST['outletName']) {
    $_POST['outletId'] = $_POST['outletName'];
}
$tsDAO = new tsDAO($dbHost,$dbUser,$dbPass,$dbname,$dbPort);
if ($tsDAO->staffEdit($_POST)) {
    $res['success'] = 1;
} else $res['success'] = 0;

echo json_encode($res);

//echo json_encode($_POST);
?>