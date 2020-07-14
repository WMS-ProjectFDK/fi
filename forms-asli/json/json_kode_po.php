<?php
session_start();
include("../../connect/conn.php");
header("Content-type: application/json");
$po = isset($_REQUEST['po']) ? strval($_REQUEST['po']) : '';

$arrData = array();
$arrNo = 0;

$sql = "select count(*) as jum from po_header where po_no='$po'";
$data = oci_parse($connect, $sql);
oci_execute($data);
$row = oci_fetch_object($data);

if (intval($row->JUM) == 0){
    $arrData[$arrNo] = array("kode"=>'SUCCESS');
}else{
	$arrData[$arrNo] = array("kode"=>'PO No. Already exist');
}
echo json_encode($arrData);
?>