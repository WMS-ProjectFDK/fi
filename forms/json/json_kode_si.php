<?php
session_start();
include("../../connect/conn.php");
header("Content-type: application/json");

$arrData = array();
$arrNo = 0;

$sql = "select dbo.SI_NO()";
$data = sqlsrv_query($connect, strtoupper($sql));
$row = sqlsrv_fetch_array($data);
$kd =  $row[0];
if ($data) {
    if(strlen($kd)==12){
        $arrData[$arrNo] = array("kode"=>$kd);
    }
}else{
	$arrData[$arrNo] = array("kode"=>"UNDEFINIED");
}
echo json_encode($arrData);
?>