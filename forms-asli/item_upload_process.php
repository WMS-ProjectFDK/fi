<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
session_start();
include "../class/excel_reader.php";
include("../connect/conn.php");

$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount($sheet_index=0);
$user = htmlspecialchars($_REQUEST['user_name']);
$success = 0;
$failed = 0;
$ins = '';

for($i=4;$i<=$hasildata;$i++){
	$net = trim($data->val($i,13));
	$item_no = trim($data->val($i,2));
	$gw = trim($data->val($i,14));
	$nw = trim($data->val($i,13));
	$tinggi = trim($data->val($i,18));
	$step = trim($data->val($i,20));
	$two_feet = trim($data->val($i,21));
	$four_feet = trim($data->val($i,23));		
	$pallet_ctn = trim($data->val($i,7));
	$pallet_pcs = trim($data->val($i,9));
	$panjang = trim($data->val($i,16)) ;
	$lebar = trim($data->val($i,17)) ;
	$pallet_size = trim($data->val($i,12)) ;
	$pi_no = trim($data->val($i,25)) ;
	$description = trim($data->val($i,4)) ;
	$drawing_number = trim($data->val($i,15)) ;
	$ctn_gross_weight = trim($data->val($i,6)) ;
	
	if($net != ''){
		$kode = "select pallet_size_type_code,panjang,lebar from pallet_size_type where upper(pallet_size_type_name) = trim('$pallet_size')";
		$data_kd = oci_parse($connect, $kode);
		oci_execute($data_kd);
		$dt_kode = oci_fetch_object($data_kd);

		$pallet_id = $dt_kode->PALLET_SIZE_TYPE_CODE;
		$panjang_1 = $dt_kode->PANJANG;
		$lebar_1 = $dt_kode->LEBAR;

		$ins = "update item set PI_NO = '$pi_no',DESCRIPTION =  '$description',ctn_gross_weight = '$ctn_gross_weight'  where item_no = '$item_no'";
		$data_ins = oci_parse($connect, $ins);
		oci_execute($data_ins);
		// $insx = $ins;
		$pesan = oci_error($data_ins);
		$msg = $pesan['message'];
		if($msg == ''){
			$success1++;
		}else{
			$msg .= " Error pada proses update ITEM Query $ins";
			break;
		}

		$ins = "delete from packing_information where pi_no = '$pi_no'";
		$data_ins = oci_parse($connect, $ins);
		oci_execute($data_ins);
		$pesan = oci_error($data_ins);
		$msg = $pesan['message'];
		if($msg == ''){
			$success1++;
		}else{
			$msg .= " Error pada proses delete PI Query $ins";
			break;
		}

		$ins = "insert into packing_information (pi_no,plt_spec_no,pallet_unit_number,pallet_ctn_number,pallet_step_ctn_number,pallet_height,pallet_width,pallet_depth,pallet_size_type) values ('$pi_no','$drawing_number','$pallet_pcs','$pallet_ctn','$step','$tinggi','$lebar','$panjang','$pallet_id') ";
		$data_ins = oci_parse($connect, $ins);
		oci_execute($data_ins);
		$pesan = oci_error($data_ins);
		$msg = $pesan['message'];
		if($msg == ''){
			$success1++;
		}else{
			$msg .= " Error pada proses insert PI Query $ins";
			break;
		}

		$ins = "delete from ztb_item where item_no = '$item_no'";
		$data_ins = oci_parse($connect, $ins);
		oci_execute($data_ins);
		$pesan = oci_error($data_ins);
		$msg = $pesan['message'];
		if($msg == ''){
			$success1++;
		}else{
			$msg .= " Error pada  delete ITEM Query $ins";
			break;
		}

		$ins = "insert into ztb_item (item_no,gw_pallet,nw_pallet,carton_height,panjang_pallet,lebar_pallet,step,two_feet,four_feet,pallet_ctn,pallet_pcs) values ('$item_no',$gw,$nw,$tinggi,$panjang_1,$lebar_1,$step,$two_feet,$four_feet,$pallet_ctn,$pallet_pcs)";
		
		$data_ins = oci_parse($connect, $ins);
		oci_execute($data_ins);
		$pesan = oci_error($data_ins);
		$msg = $pesan['message'];
		if($msg == ''){
			$success++;
		}else{
			$msg .= " Error pada  insert item Query $ins";
			break;
		}
	}
}

$ins = "delete from packing_information where pi_no is null";
$data_ins = oci_parse($connect, $ins);
oci_execute($data_ins);

if($msg == ''){
	echo json_encode("Success = ".$success."");
}else{
	echo json_encode("".$msg."");
}	
?>