<?php
session_start();
include("../../connect/conn.php");
header("Content-type: application/json");
$arrNo = 0;

$sql = "select 'FI-'+ RIGHT(cast(YEAR(getdate()) as varchar),2)+'-'+
    COALESCE(RIGHT(replicate('0',4)+ cast(cast(max(substring(so_no,7,4)) as int)+1 as varchar(5)), 4),'0001') 
    as SO_NO
	from SO_HEADER
	where substring(so_no,4,2) = RIGHT(cast(YEAR(getdate()) as varchar),2)";
$data = sqlsrv_query($connect, strtoupper($sql));
$row = sqlsrv_fetch_object($data);
$kd =  $row->SO_NO;
if ($data) {
    $arrData[$arrNo] = array("kode"=>$kd);
}else{
	$arrData[$arrNo] = array("kode"=>"UNDEFINIED");
}
echo json_encode($arrData);
?>