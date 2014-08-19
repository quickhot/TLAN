<?php
include_once '../conf/mysql.php';
include_once '../classes/MysqlDB.class.php';

$brandId = $_GET["brand"];//取得商户ID
$newConn = new MysqlDB(DBHOST,DBUSER,DBPASS,DBNAME);

if(isset($brandId)){ 
   //通过BRANDID获取商品ID和商品名称
    $result=mysql_query("SELECT id,productName FROM product WHERE brandId=$brandId",$newConn->link); 
    while($row=mysql_fetch_array($result,MYSQL_NUM)){ 
        $select[] = array("ProductID"=>$row[0],"ProductName"=>$row[1]); 
    } 
    echo json_encode($select);//json编码，输出
} 
?>