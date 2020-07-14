<?php
include("../../connect/conn2.php");
header("Content-type: application/json");

$date = date('ym');
$cek = "select count(*) as jum from ztb_prf_quotation_header where TO_CHAR(QUOTATION_DATE,'YYMM') = (SELECT TO_CHAR (SYSDATE, 'YYMM') FROM DUAL)";
$data = oci_parse($connect, $cek);
oci_execute($data);
$dt = oci_fetch_array($data);

if($dt[0] == 0){
	$code = "PP-".$date."-0001";
}else{
	$sql = "select 'PP-'||(SELECT TO_CHAR (SYSDATE, 'YYMM') FROM DUAL)||'-'||
		(select coalesce(lpad(cast(cast(substr(max(quotation_no),10,4) as number)+1 as varchar2(4)),4,'0'), '0001') from ztb_prf_quotation_header)  as kode 
		from ztb_prf_quotation_header where substr(quotation_no,4,4) = (SELECT TO_CHAR (SYSDATE, 'YYMM') FROM DUAL) group by quotation_no";
	$data2 = oci_parse($connect, $sql);
	oci_execute($data2);
	$dt2 = oci_fetch_array($data2);
	$code = $dt2[0];
}

$arrData = array();
$arrNo = 0;

$arrData[$arrNo] = array("kode"=>$code);
echo json_encode($arrData);
?>