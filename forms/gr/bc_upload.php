<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
session_start();
include "../../class/excel_reader.php";
include("../../connect/conn.php");
$success = 0;
$success = 0;

$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount($sheet_index=0);

$user = $_SESSION['id_wms'];
$success = 0;
$failed = 0;

for($i=2;$i<=$hasildata;$i++){
	$gr = trim($data->val($i,1));
	$tipe = trim($data->val($i,2));
	$no = trim($data->val($i,3));
	
	$ins = "update gr_header set bc_doc ='".$tipe."', bc_no = '".$no."' where gr_no='".$gr."' ";
	$data_ins = sqlsrv_query($connect, strtoupper($ins));
	
	if($data_ins){
		$success++;	
	}else{
		$failed++;
	}
}

echo json_encode("Success = ".$success.", Failed = ".$failed);
?>