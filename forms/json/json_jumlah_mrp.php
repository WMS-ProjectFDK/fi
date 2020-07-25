<?php
session_start();
include("../../connect/conn.php");
header("Content-type: application/json");

$arrData = array();
$arrNo = 0;

$sql = "select count(distinct item_no) as jum from ztb_mrp_data";
$data = sqlsrv_query($connect, strtoupper($sql));
$row = sqlsrv_fetch_object($data);

$sql2 = "select count(distinct item_no) as pmbgi from ztb_material_konversi";
$data2 = sqlsrv_query($connect, strtoupper($sql2));
$row2 = sqlsrv_fetch_object($data2);

$persen = number_format(($row->JUM/$row2->PMBGI)*100,1);

$arrData[$arrNo] = array("kode"=>$row->JUM, "hasil"=>$row2->PMBGI, "persen"=>$persen);
echo json_encode($arrData);
?>