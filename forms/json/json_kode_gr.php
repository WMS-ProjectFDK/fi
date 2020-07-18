<?php
session_start();
include("../../connect/conn.php");
header("Content-type: application/json");
$gr = isset($_REQUEST['gr']) ? strval($_REQUEST['gr']) : '';
$gr_date = isset($_REQUEST['gr_date']) ? strval($_REQUEST['gr_date']) : '';

$arrData = array();
$arrNo = 0;

$sql = "select count(*) as jum from gr_header where gr_no='$gr'";
$data = sqlsrv_query($connect, strtoupper($sql));
$row = sqlsrv_fetch_object($data);

if (intval($row->JUM) == 0){
    $sql2 = "select count(*) as j from whinventory 
		where this_month = (SELECT CONVERT(nvarchar(6), cast('2020-07-01' as date), 112))";

    $data2 = sqlsrv_query($connect, strtoupper($sql2));
	$row2 = sqlsrv_fetch_object($data2);
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