<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
ini_set('memory_limit', '-1');
set_time_limit(0);
session_start();
include("../connect/conn.php");
include ("sscc_func.php");

if (isset($_SESSION['id_wms'])){
	$fp = fopen('mps_download_result.json', 'w');
	fwrite($fp, json_encode($response));
	fclose($fp);
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
	echo json_encode($msg);
}else{
	$items = array('msg' => 'success', 'success' => $success, 'failed' => $failed, 'pallet' => $pallet, 'carton' => $carton);
	echo json_encode($items);
}
?>