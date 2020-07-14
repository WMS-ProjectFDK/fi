<?php
session_start();
include("../../connect/conn.php");
header("Content-type: application/json");
$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
$user_name = $_SESSION['id_wms'];

$arrData = array();
$arrNo = 0;

$sql = "select count(*) as jum from ztb_material_konversi where item_no='$item'";
$data = oci_parse($connect, $sql);
oci_execute($data);
$row = oci_fetch_object($data);


if (intval($row->JUM) == 0 or $user_name = 'FI0084'){
    $arrData[$arrNo] = array("kode"=>'NO');
}else{
	$arrData[$arrNo] = array("kode"=>'YES');
}
echo json_encode($arrData);
?>