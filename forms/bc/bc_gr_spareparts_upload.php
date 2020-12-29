<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
session_start();
include "../../class/excel_reader.php";
include("../../connect/conn.php");

$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount();

$success = 0;
$failed = 0;
$msg = '';

for ($k=2; $k<=$hasildata; $k++) {
	$gr = trim($data->val($k,5));
	$bcdoc = trim($data->val($k,2));
	$bcno = trim(str_replace("'", "''", $data->val($k,3)));

	$upd = "update sp_gr_header set bc_doc='$bcdoc', bc_no ='$bcno' where gr_no='$gr'";
    $dataNya = sqlsrv_query($connect, $upd);
    
    if($dataNya === false ){
        $msg .= sqlsrv_errors();
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