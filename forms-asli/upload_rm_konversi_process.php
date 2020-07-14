<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
session_start();
include "../class/excel_reader.php";
include("../connect/conn.php");

$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount($sheet_index=0);
$user = htmlspecialchars($_REQUEST['user_name']);
$success = 0;		$failed = 0;		$itm = '';

for($i=2;$i<=$hasildata;$i++){
	$assy_ln = trim($data->val($i,1));
	$cell_ty = trim($data->val($i,2));
	$item_no = trim($data->val($i,3));
	$konvrsi = trim($data->val($i,7));
	$min_day = trim($data->val($i,8));
	$average = trim($data->val($i,9));
	$max_day = trim($data->val($i,10));

	$del = "delete from ztb_material_konversi where assy_line = '$assy_ln' and cell_type = '$cell_ty' AND item_no = $item_no ";
	$data_del = oci_parse($connect, $del);
	oci_execute($data_del);
	$pesan = oci_error($data_del);
	$msg = $pesan['message'];
	if($msg != ''){
		$msg .= "Error pada proses delete konversi $del";
		break;
	}

	if($itm != $item_no){
		$upd = "update ztb_config_rm set min_days = $min_day, average = $average , max_days = $max_day where item_no = '$item_no' ";
		$data_upd = oci_parse($connect, $upd);
		oci_execute($data_upd);
		$pesan = oci_error($data_ins);
		$msg = $pesan['message'];
		if($msg != ''){
			$msg .= " Error pada proses update configurasi $upd";
			break;
		}
	}

	$cek = "select count(item_no) as jum from ztb_config_rm where item_no=$item_no";
	$data_cek = oci_parse($connect, $cek);
	oci_execute($data_cek);
	$pesan = oci_error($data_cek);
	$msg = $pesan['message'];
	if($msg != ''){
		$msg .= " Error pada proses cek item Config $cek";
		break;
	}
	$dt_cek = oci_fetch_object($data_cek);
	if($dt_cek->JUM == 0){
		$ins_conf = "insert into ztb_config_rm (item_no,tipe, min_days, max_days, remark, average, flag_details)
				select a.item_no, a.description, $min_day, $max_day, 100, $average, 0 from item a where a.item_no=$item_no";
		$data_ins_conf = oci_parse($connect, $ins_conf);
		oci_execute($data_ins_conf);
		$pesan = oci_error($data_ins_conf);
		$msg = $pesan['message'];
		if($msg != ''){
			$msg .= " Error pada proses insert new item Config $ins_conf";
			break;
		}
	}

	$ins = "insert into ztb_material_konversi (assy_line, cell_type, item_no, konversi) VALUES ('$assy_ln','$cell_ty',$item_no,$konvrsi)";
	$data_ins = oci_parse($connect, $ins);
	oci_execute($data_ins);
	$pesan = oci_error($data_ins);
	$msg = $pesan['message'];
	if($msg == ''){
		$success++;
	}else{
		$msg .= " Error pada proses insert konversi $ins";
		$failed++;
		break;
	}

	$itm = $item_no;
}

if($msg == ''){
	echo json_encode("Success = ".$success.", Failed = ".$failed);
}else{
	echo json_encode("".$msg."");
}

?>