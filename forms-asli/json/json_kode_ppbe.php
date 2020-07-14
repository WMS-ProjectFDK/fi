<?php
session_start();
include("../../connect/conn.php");
header("Content-type: application/json");
$arrNo = 0;

$user = isset($_REQUEST['user']) ? strval($_REQUEST['user']) : '';

$sql = "select nvl(cast(max(a.no) as integer)+1,1) || '/' || substr(b.person,0,1) as ppbe_no 
	from ztb_ppbe a 
 	inner join person b on a.person_code = b.person_code
 	where a.person_code='$user' and a.period = (select to_char(sysdate,'YYYY') from dual)
 	group by b.person";
$data = oci_parse($connect, $sql);
oci_execute($data);

$row = oci_fetch_object($data);
$kd =  $row->PPBE_NO;
IF ($kd==''){
	$kd='001/X';
}
$arrData[$arrNo] = array("kode"=>$kd);
echo json_encode($arrData);
?>