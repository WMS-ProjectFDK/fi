<?php
session_start();
include("../../connect/conn.php");
header("Content-type: application/json");
$dn_no =  = strval($_REQUEST['dn_no']);

$sql = "select count(*) as jum from dn_header where dn_no='$dn_no'";
$data = sqlsrv_query($connect, strtoupper($sql));
$row = sqlsrv_fetch_object($data);
$kd =  intval($row->JUM);
$arrData[$arrNo] = array("kode"=>$kd);
echo json_encode($arrData);
?>