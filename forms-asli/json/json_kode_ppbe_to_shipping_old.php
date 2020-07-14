<?php
session_start();
include("../../connect/conn.php");
header("Content-type: application/json");
$arrNo = 0;

$user = isset($_REQUEST['user']) ? strval($_REQUEST['user']) : '';

$cek_user = "select substr(person,0,1) from person where person_code='$user' ";
$data_user = oci_parse($connect, $cek_user);
oci_execute($data_user);
$rowArr = oci_fetch_array($data_user);

$sql = "select nvl(cast(max(substr(crs_remark, 1, Instr(crs_remark, '/', -1, 1) -1)) as integer)+1,1) || '/' || '".$rowArr[0]."' as ppbe_no
	from answer a
	where data_date > '01-JUL-18' and substr(crs_remark, -1, 1) = '".$rowArr[0]."'";
$data = oci_parse($connect, $sql);
oci_execute($data);

$row = oci_fetch_object($data);
$kd =  $row->PPBE_NO;
$arrData[$arrNo] = array("kode"=>$kd);
echo json_encode($arrData);
?>