<?php
include '../dbInc.php';
include '../class/tsDAO.php';
include_once '../lib/loginStatus.php';
$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction

$page = $_REQUEST['page']; // get the requested page
$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
$sord = $_REQUEST['sord']; // get the direction

if(!$sidx) $sidx =1;
//if(!$limit) $limit=10000;
//if(!$page) $page=1;
// connect to the database

$totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
if($totalrows) {
	$limit = $totalrows;
}
//$count="4";
// $sql = "SELECT COUNT(*) as count FROM userInfo";
// $row = $db->fetchAllData($sql);
// //$row = mysql_fetch_array($result,MYSQL_ASSOC);
// $count = $row[0]['count'];
$tsDAO = new tsDAO($dbHost,$dbUser,$dbPass,$dbname,$dbPort);
$count = $tsDAO -> getCountStaff();

if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} else {
	$total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)


$result = $tsDAO->getStaff($db, $ESId,$sidx,$sord,$start,$limit);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
foreach ($result as $arr){
	$responce->rows[$i]['id'] = $arr['0'];
	$responce->rows[$i]['cell'] = $arr;
	$i++;
}

echo json_encode($responce);
?>
