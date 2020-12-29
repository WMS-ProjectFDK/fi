<?php
set_time_limit(0);
include("../../../connect/conn.php");
date_default_timezone_set('Asia/Jakarta');
$arrData = array();
$arrNo = 0;
$msg = '';

$msg .= date("Y-m-d H:i:s").' - START<br/>';
$sql = "{call zsp_mrp_pm}";
$stmt = sqlsrv_query($connect, $sql);
$msg .= date("Y-m-d H:i:s").'PROCESS<br>';
if( $stmt === false ){
	die( print_r( sqlsrv_errors(), true));
}
$msg .= date("Y-m-d H:i:s").' - FINISH<br/>';
echo json_encode('success');
?>