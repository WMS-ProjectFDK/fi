<?php
session_start();
include("../../connect/conn.php");
header("Content-type: application/json");
$ppbe = isset($_REQUEST['ppbe']) ? strval($_REQUEST['ppbe']) : '';

$arrData = array();
$arrNo = 0;

$sql = "select count(*) as jum from answer where crs_remark='$ppbe'";
$data = oci_parse($connect, $sql);
oci_execute($data);
$row = oci_fetch_object($data);

if (intval($row->JUM) == 0){
    $arrData[$arrNo] = array("kode"=>'SUCCESS');
}else{
	$arrData[$arrNo] = array("kode"=>'PPBE No. Already exist');
}
echo json_encode($arrData);
?>