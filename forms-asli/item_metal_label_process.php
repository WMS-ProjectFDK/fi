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

for($i=2;$i<=$hasildata;$i++){
	$item_no = trim($data->val($i,1));
	$inkjet_code = trim($data->val($i,2));
	$period = trim($data->val($i,3));
	$drawing_no = trim($data->val($i,4));
	$remark = trim($data->val($i,5));
	
	if($item_no != ''){
		$cek = "select count(*) as jum from ztb_item_label_inkjet_code where item_no=$item_no";
		$data_cek = oci_parse($connect, $cek);
		oci_execute($data_cek);
		$dt_kode = oci_fetch_object($data_cek);

		if($dt_kode->JUM > 0){
			$qry = "update ztb_item_label_inkjet_code set 
				inkjet_code=$inkjet_code, 
				period=$period, 
				drawing_no='$drawing_no', 
				upto_date=(select sysdate from dual),
				remark='$remark' 
			where item_no=$item_no";
		}else{
			$qry = "insert into ztb_item_label_inkjet_code (item_no, inkjet_code, period,drawing_no, upto_date, remark)
				select $item_no, $inkjet_code, $period, '$drawing_no', sysdate, '$remark' from dual ";	
		}

		$data_qry = oci_parse($connect, $qry);
		oci_execute($data_qry);
		
		$pesan = oci_error($data_qry);
		$msg = $pesan['message'];
		if($msg == ''){
			$success++;
		}else{
			$msg .= " Error pada proses insert/update inkjet item Query $qry";
			$failed++;
			break;
		}
	}
}

if($msg == ''){
	echo json_encode("Success = ".$success."");
}else{
	echo json_encode("".$msg."");
}	
?>