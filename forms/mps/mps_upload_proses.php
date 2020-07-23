<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");

session_start();
$user = $_SESSION['id_wms'];

include ("../../class/excel_reader.php");
include ("../../connect/conn.php");

$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount($sheet_index=0);

$success = 0;       $failed = 0;

for($i=5;$i<=$hasildata;$i++){
    // $item = trim($data->val($i,1));
    // $bln = intval($data->val($i,2));
    // $year = trim($data->val($i,3));
    // $qty = trim($data->val($i,4));
    // $sts_bundle = trim($data->val($i,5));
    // $qty_b = trim($data->val($i,6));
    
    // delete MPS_HEADER
    // delete from MPS_DETAILS
    // INSERT MPS HEADER
    // INSERT MPS DETAILS
    // INSERT MPS_HEADER_RIREKI
    // INSERT MPS_DETAILS_RIREKI
    // DELETE MPS_REMAIN_HISTORY
    // INSERT MPS_REMAIN_HISTORY
    // INSERT MPS_REMAIN
    // UPDATE MPS_REMAIN
    // DELETE PRODUCT_PLAN_HISTORY
    // INSERT PRODUCT_PLAN_HISTORY
    // INSERT PRODUCT_PLAN
    
    

    // if ($qty_b==''){
    // 	$qty_bundle = 1;
    // }else{
    // 	$qty_bundle = $qty_b;
    // }

    // $cek = "select count(*) as jml from ztb_safety_stock where period=".$bln." and year='".$year."' and item_no=".$item."";
    // $data_cek = oci_parse($connect, $cek);
    // oci_execute($data_cek);
    // $dt_cek = oci_fetch_object($data_cek);

    // if(floatval($dt_cek->JML) == 0){
    // 	$ins = "insert into ztb_safety_stock VALUES (".$item.",".$bln.",'".$year."',".$qty.",1,'".$sts_bundle."',".$qty_bundle.")";
    // 	$data_ins = oci_parse($connect, $ins);
    // 	oci_execute($data_ins);
    // 	if($data_ins){
    // 		$success++;	
    // 	}else{
    // 		$failed++;
    // 	}
    // }else{
    // 	$ins2 = "update ztb_safety_stock set qty=".$qty.", upload=upload+1 where item_no=".$item." and period=".$bln." and year='$year'";//"insert into ztb_safety_stock VALUES(".$item.",".$bln.",'".$year."',".$qty.",2)";
    // 	$data_ins2 = oci_parse($connect, $ins2);
    // 	oci_execute($data_ins2);
    // 	if($data_ins2){
    // 		$success++;	
    // 	}else{
    // 		$failed++;
    // 	}
    // }
}
echo json_encode("Success = ".$success.", Failed = ".$failed);
?>