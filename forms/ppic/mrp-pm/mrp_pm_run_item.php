<?php
// error_reporting(1);
set_time_limit(0);
date_default_timezone_set('Asia/Jakarta');

include("../../../connect/conn.php");
$item = htmlspecialchars($_REQUEST['item']);

$msg = 'success';

$sqlx = "{call zsp_mrp_pm_item(?)}";
$params2 = array(
    array($item, SQLSRV_PARAM_IN)
);
$stmt = sqlsrv_query($connect, $sqlx,$params2);

if($stmt === false){
    $msg = die(print_r( sqlsrv_errors(), true));
}else{
    echo json_encode($msg);
}
?>