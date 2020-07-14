<?php
session_start();
include("../../connect/conn.php");
header("Content-type: application/json");
$gr = isset($_REQUEST['gr']) ? strval($_REQUEST['gr']) : '';
$gr_date = isset($_REQUEST['gr_date']) ? strval($_REQUEST['gr_date']) : '';

$arrData = array();
$arrNo = 0;

$sql = "select count(*) as jum from gr_header where gr_no='$gr'";
$data = oci_parse($connect, $sql);
oci_execute($data);
$row = oci_fetch_object($data);

if (intval($row->JUM) == 0){
    $sql2 = "select count(*) as j from whinventory where this_month = to_char(to_date('$gr_date','yyyy-mm-dd'),'yyyymm')";
    $data2 = oci_parse($connect, $sql2);
	oci_execute($data2);
	$row2 = oci_fetch_object($data2);
	if (intval($row2->J) > 0){
		$arrData[$arrNo] = array("kode"=>'SUCCESS');
	}else{
		$arrData[$arrNo] = array("kode"=>'Period Inventory not Found');	
	}
}else{
	$arrData[$arrNo] = array("kode"=>'Goods Receive No. Already exist');
}
echo json_encode($arrData);
?>