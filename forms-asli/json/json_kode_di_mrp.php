<?php
session_start();
header("Content-type: application/json");
include("../../connect/conn.php");

$arrData = array();
$arrNo = 0;

$sql = "select 'DIMRP-'|| to_char(sysdate,'YYMMDD') || nvl(lpad(cast(cast(max(substr(di_no,-3)) as integer)+1 as varchar(3)),3,'0'),'001') as di_no
	from di_header
	where di_date >= '01-OCT-18' and substr(di_no,0,6)='DIMRP-' and substr(di_no,7,6)= (select to_char(sysdate,'YYMMDD') from dual)";
$data = oci_parse($connect, $sql);
$d = oci_execute($data);
$row = oci_fetch_object($data);
$kd =  $row->DI_NO;

if ($d) {
    $arrData[$arrNo] = array("kode"=>$kd);
}else{
	$arrData[$arrNo] = array("kode"=>"UNDEFINIED");
}
echo json_encode($arrData);
?>