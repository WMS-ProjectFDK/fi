<?php
session_start();
include("../../../../connect/conn.php");
header("Content-type: application/json");
$po = isset($_REQUEST['po']) ? strval($_REQUEST['po']) : '';

$arrData = array();
$arrNo = 0;

$sql = "select count(*) as jum from sp_po_header where po_no='$po'";
$data = sqlsrv_query($connect, $sql);
$row = sqlsrv_fetch_object($data);

if (intval($row->jum) == 0){
    $arrData[$arrNo] = array("kode"=>'SUCCESS');
}else{
	$arrData[$arrNo] = array("kode"=>'PO No. Already exist');
}
echo json_encode($arrData);
?>