<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
session_start();
include "../class/excel_reader.php";
include("../connect/conn.php");

$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount();

$success = 0;
$failed = 0;
$msg = '';

for ($k=2; $k<=$hasildata; $k++) {
	$gr = trim($data->val($k,5));
	$bcdoc = trim($data->val($k,2));
	$bcno = trim(str_replace("'", "''", $data->val($k,3)));

	$upd = "update gr_header set bc_doc='$bcdoc', bc_no ='$bcno' where gr_no='$gr'";
	$dataNya = oci_parse($connect, $upd);
	oci_execute($dataNya);	
	$pesan = oci_error($dataNya);
	$msg = $pesan['message'];
	if($msg != ''){
		$msg .= " Error pada proses update Receive Item (Query: $upd";
		break;
	}else{
		$success++;
	}
}

if($msg == ''){
	echo json_encode("Success = ".$success." data uploaded");
}else{
	echo json_encode("ErrorMsg = ".$msg."");
}
?>