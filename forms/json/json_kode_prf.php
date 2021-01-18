<?php
session_start();
include("../../connect/conn.php");
header("Content-type: application/json");

$kode='O.PRF-'.date('y')."-";
$arrData = array();
$arrNo = 0;

$sql = "select '$kode' + ISNULL(REPLACE(STR(max(substring(prf_no,10,5))+1,5),' ','0'),'00001') as PRF_NO
    from prf_header 
    where SUBSTRING(PRF_NO,1,9) = '$kode' ";

// echo $sql;

$data = sqlsrv_query($connect, $sql);

$row = sqlsrv_fetch_object($data);
$kd =  $row->PRF_NO;
if ($data) {
    $arrData[$arrNo] = array("kode"=>$kd);
}else{
	$arrData[$arrNo] = array("kode"=>"UNDEFINIED");
}
echo json_encode($arrData);
?>