<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
session_start();
include "../../class/excel_reader.php";
include("../../connect/conn.php");

$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount($sheet_index=0);

$user = htmlspecialchars($_REQUEST['user_name']);
$success = 0;
$failed = 0;
$ins = '';

for($i=2;$i<=$hasildata;$i++){
	$item_no = trim($data->val($i,1));
	$shrink = trim($data->val($i,2));
	$blister = trim($data->val($i,3));
	$inner = trim($data->val($i,4));
	$medium=trim($data->val($i,5));
	$outer=trim($data->val($i,6));
	$berat = trim($data->val($i,7));
	$plus = trim($data->val($i,8));
	$minus=trim($data->val($i,9));
	$isi=trim($data->val($i,10));

	if ($item_no != ''){
		$ins = "delete from ztb_itf_code where item_no = '$item_no'";
		$data_ins = sqlsrv_query($connect, strtoupper($ins));

		$ins = "insert into ZTB_ITF_code (Item_no,SHIRNK,Blister,Inner,Medium,Outer,berat_inner,toleransi_plus,toleransi_minus,isi_inner) values ('$item_no','$shrink','$blister','$inner','$medium','$outer','$berat','$plus','$minus','$isi')";
		$data_ins = sqlsrv_query($connect, strtoupper($ins));
	
		if($data_ins){
			$success++;	
		}else{
			$failed++;
		}
	}
}
echo json_encode("Success = ".$i.", Failed = ".$failed."");
?>