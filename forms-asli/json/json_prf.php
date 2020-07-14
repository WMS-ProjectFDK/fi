<?php
include("../../connect/conn2.php");
header("Content-type: application/json");

$cek = "select count(*) as jum from ztb_prf_req_header where TO_CHAR(REQ_DATE,'YYMM') = (SELECT TO_CHAR (SYSDATE, 'YYMM') FROM DUAL)";
$data = oci_parse($connect, $cek);
oci_execute($data);
$dt = oci_fetch_array($data);

if($dt[0] == 0){
	$code = "PRF-".date('ym')."-0001";
}else{
	$sql = "select 'PRF-'|| 
		(SELECT TO_CHAR (SYSDATE, 'YYMM') FROM DUAL)||'-'||
		(select coalesce(lpad(cast(cast(substr(max(req_no),10,4) as number)+1 as varchar2(4)),4,'0'), '0001') from ztb_prf_req_header)  as kode 
		from ztb_prf_req_header
		where substr(req_no,5,4) = (SELECT TO_CHAR (SYSDATE, 'YYMM') FROM DUAL)
		group by req_no";
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