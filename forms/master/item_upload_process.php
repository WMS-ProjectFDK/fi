<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
session_start();
include "../../class/excel_reader.php";
include("../../connect/conn.php");

$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount($sheet_index=0);
$user = $_SESSION['id_wms'];//htmlspecialchars($_REQUEST['user_name']);
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
		$kode = "select pallet_size_type_code,panjang,lebar from pallet_size_type where upper(pallet_size_type_name) = '$pallet_size' ";
		$data_kd = sqlsrv_query($connect, $kode);
		$dt_kode = sqlsrv_fetch_object($data_kd);

		$pallet_id = $dt_kode->pallet_size_type_code;
		$panjang_1 = $dt_kode->panjang;
		$lebar_1 = $dt_kode->lebar;

		$upd = "update item set PI_NO = '$pi_no',DESCRIPTION =  '$description',ctn_gross_weight = '$ctn_gross_weight'  where item_no = '$item_no'";
		$data_upd = sqlsrv_query($connect, $upd);

		if( $data_upd === false ) {
			if( ($errors = sqlsrv_errors() ) != null) {  
		        foreach( $errors as $error){ 
		            $msg .= $error[ 'message']."<br/>";  
		        }  
		    }
		}

		if($msg == ''){
			$success1++;
		}else{
			$msg .= " Error pada proses update ITEM Query $ins";
			break;
		}

		$del = "delete from packing_information where pi_no = '$pi_no'";
		$data_del = sqlsrv_query($connect, $del);
		
		if( $data_del === false ) {
			if( ($errors = sqlsrv_errors() ) != null) {  
		        foreach( $errors as $error){ 
		            $msg .= $error[ 'message']."<br/>";  
		        }  
		    }
		}

		if($msg == ''){
			$success1++;
		}else{
			$msg .= " Error pada proses delete PI Query $ins";
			break;
		}

		$ins = "insert into packing_information (pi_no,plt_spec_no,pallet_unit_number,pallet_ctn_number,pallet_step_ctn_number,pallet_height,pallet_width,pallet_depth,pallet_size_type) values ('$pi_no','$drawing_number','$pallet_pcs','$pallet_ctn','$step','$tinggi','$lebar','$panjang','$pallet_id') ";
		$data_ins = sqlsrv_query($connect, $ins);
		
		if( $data_ins === false ) {
			if( ($errors = sqlsrv_errors() ) != null) {  
		        foreach( $errors as $error){ 
		            $msg .= $error[ 'message']."<br/>";  
		        }  
		    }
		}

		if($msg == ''){
			$success1++;
		}else{
			$msg .= " Error pada proses insert PI Query $ins";
			break;
		}

		$del2 = "delete from ztb_item where item_no = '$item_no'";
		$data_del2 = sqlsrv_query($connect, $del2);

		if( $data_del2 === false ) {
			if( ($errors = sqlsrv_errors() ) != null) {  
		        foreach( $errors as $error){ 
		            $msg .= $error[ 'message']."<br/>";  
		        }  
		    }
		}

		if($msg == ''){
			$success1++;
		}else{
			$msg .= " Error pada  delete ITEM Query $ins";
			break;
		}

		$ins2 = "insert into ztb_item (item_no,gw_pallet,nw_pallet,carton_height,panjang_pallet,lebar_pallet,step,two_feet,four_feet,pallet_ctn,pallet_pcs) values ('$item_no',$gw,$nw,$tinggi,$panjang_1,$lebar_1,$step,$two_feet,$four_feet,$pallet_ctn,$pallet_pcs)";
		$data_ins2 = sqlsrv_query($connect, $ins2);

		if( $data_ins2 === false ) {
			if( ($errors = sqlsrv_errors() ) != null) {  
		        foreach( $errors as $error){ 
		            $msg .= $error[ 'message']."<br/>";  
		        }  
		    }
		}

		if($msg == ''){
			$success++;
		}else{
			$msg .= " Error pada  insert item Query $ins";
			break;
		}
	}
}

$del3 = "delete from packing_information where pi_no is null";
$data_del3 = sqlsrv_query($connect, $del3);

if($msg == ''){
	echo json_encode("Success = ".$success."");
}else{
	echo json_encode("".$msg."");
}	
?>