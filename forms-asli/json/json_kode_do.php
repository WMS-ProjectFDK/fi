<?php
session_start();
include("../../connect/conn.php");
header("Content-type: application/json");
$arrNo = 0;

$do = isset($_REQUEST['do']) ? strval($_REQUEST['do']) : '';

$sql = "select count(*) as do_no from do_header where do_no='$do'";
$data = oci_parse($connect, $sql);
oci_execute($data);
$row = oci_fetch_object($data);
$kd =  $row->DO_NO;
if ($kd > 0) {
    $arrData[$arrNo] = array("kode"=>"ALREADY");
}else{
	$arrData[$arrNo] = array("kode"=>"NEW_DO");
}
echo json_encode($arrData);
?>