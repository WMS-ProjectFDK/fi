<?php
header("Content-type: application/json");
session_start();
ini_set('max_execution_time', -1);
include("../connect/conn.php");

$wo_no = isset($_REQUEST['wo_no']) ? strval($_REQUEST['wo_no']) : '';

$arrData = array();
$arrNo = 0;

$sql = "select From_carton,to_carton,Total_Carton,Quantity,Item,start_carton from ztb_amazon_wo 
	where wo='$wo_no'";
// echo $sql;
$data = oci_parse($connect, $sql);
oci_execute($data);

while ($row=oci_fetch_object($data)){
	array_push($arrData, $row);
	$q = $arrData[$arrNo]->QUANTITY;
	$arrData[$arrNo]->QUANTITY = number_format($q);

	$t = $arrData[$arrNo]->TOTAL_CARTON;
	$arrData[$arrNo]->TOTAL_CARTON = number_format($t);
	$arrNo++;
}

$result["rows"] = $arrData;
echo json_encode($arrData);
?>