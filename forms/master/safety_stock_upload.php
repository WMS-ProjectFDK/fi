<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
session_start();
include "../../class/excel_reader.php";
include("../../connect/conn.php");

$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount($sheet_index=0);

$user = $_SESSION['id_wms'];
$success = 0;
$failed = 0;

	for($i=2;$i<=$hasildata;$i++){
		$item = trim($data->val($i,1));
		$bln = intval($data->val($i,2));
		$year = trim($data->val($i,3));
		$qty = trim($data->val($i,4));
		$sts_bundle = trim($data->val($i,5));
		$qty_b = trim($data->val($i,6));

		if ($qty_b==''){
			$qty_bundle = 1;
		}else{
			$qty_bundle = $qty_b;
		}

		$cek = "select count(*) as jml from ztb_safety_stock where period=".$bln." and year='".$year."' and item_no=".$item."";
		$data_cek = sqlsrv_query($connect, $cek);
		$dt_cek = sqlsrv_fetch_object($data_cek);

		if(floatval($dt_cek->jml) == 0){
			$ins = "insert into ztb_safety_stock VALUES (".$item.",".$bln.",'".$year."',".$qty.",1,'".$sts_bundle."',".$qty_bundle.")";
			$data_ins = sqlsrv_query($connect, $ins);
			if($data_ins){
				$success++;	
			}else{
				$failed++;
			}
		}else{
			$ins2 = "update ztb_safety_stock set qty=".$qty.", upload=upload+1 where item_no=".$item." and period=".$bln." and year='$year'";
			$data_ins2 = sqlsrv_query($connect, $ins2);
			if($data_ins2){
				$success++;	
			}else{
				$failed++;
			}
		}
	}
echo json_encode("Success = ".$success.", Failed = ".$failed);
?>