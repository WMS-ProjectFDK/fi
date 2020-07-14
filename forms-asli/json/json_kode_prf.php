<?php
session_start();
include("../../connect/conn.php");
header("Content-type: application/json");

$kode='O.PRF-'.date('y')."-";
$arrData = array();
$arrNo = 0;

$sql = "select '$kode'||coalesce(lpad(cast(cast(max(substr(prf_no,-5)) as integer)+1 as varchar(5)),5,'0'),'00001') as prf_no
	from prf_header where prf_no like '%$kode%'";
$data = oci_parse($connect, $sql);
oci_execute($data);
$row = oci_fetch_object($data);
$kd =  $row->PRF_NO;
if ($data) {
    $arrData[$arrNo] = array("kode"=>$kd);
}else{
	$arrData[$arrNo] = array("kode"=>"UNDEFINIED");
}
echo json_encode($arrData);
?>