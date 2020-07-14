<?php
$DI = isset($_REQUEST['di']) ? strval($_REQUEST['di']) : '';
include("../../connect/conn.php");
header("Content-type: application/json");

$date = date('ym');
$cek = "select count(*) as jum from di_header where di_no ='".trim($DI)."'";
$data = oci_parse($connect, $cek);
oci_execute($data);
$dt = oci_fetch_object($data);

$arrData = array();
$arrNo = 0;
if(floatval($dt->JUM) == 0){
	$arrData[$arrNo] = array("kode"=>floatval($dt->JUM));
}else{
	$arrData[$arrNo] = array("kode"=>floatval($dt->JUM));
}
echo json_encode($arrData);
?>