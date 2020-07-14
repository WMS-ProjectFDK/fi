<?php
session_start();
include("../../connect/conn.php");
header("Content-type: application/json");

$arrData = array();
$arrNo = 0;

$sql = "select count(distinct item_no) as jum from ztb_mrp_data_pck";
$data = oci_parse($connect, $sql);
oci_execute($data);
$row = oci_fetch_object($data);

$sql2 = "select count(distinct lower_item_no) as pmbgi from zvw_mrp_pm_konversi";
$data2 = oci_parse($connect, $sql2);
oci_execute($data2);
$row2 = oci_fetch_object($data2);

$persen = number_format(($row->JUM/$row2->PMBGI)*100,1);

$arrData[$arrNo] = array("kode"=>$row->JUM, "hasil"=>$row2->PMBGI, "persen"=>$persen);
echo json_encode($arrData);
?>