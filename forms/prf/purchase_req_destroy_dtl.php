<?php
session_start();
error_reporting(0);
include("../../connect/conn.php");
$msg = '';

$prf = strval($_REQUEST['prf']);
$item = strval($_REQUEST['item']);
$line = strval($_REQUEST['line']);

$del = "delete from prf_details where prf_no='".$prf."' and item_no=".$item." and line_no='".$line."'";
$data_del = sqlsrv_query($connect, $del);

$pesan = sqlsrv_errors($upd3);
$msg .= $pesan['message'];

if($msg != ''){
	echo json_encode(array('errorMsg'=>'Delete Item Process Error  : $del'));
	break;
}else{
	echo json_encode(array('success'=>true));
}

?>