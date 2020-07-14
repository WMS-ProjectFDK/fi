<?php
date_default_timezone_set('Asia/Jakarta');
include("../../connect/conn.php");
$id_prin = isset($_REQUEST['id_prin']) ? strval($_REQUEST['id_prin']) : '';
$id_plan = isset($_REQUEST['id_plan']) ? strval($_REQUEST['id_plan']) : '';

$now=date('Y-m-d H:i:s');

if(! $connect){
	echo json_encode(array('errorMsg'=>'CONNECT TO SERVER FAILED ... !!'));
}else{
	$ins ="insert into ztb_assy_heating 
		(select $id_prin,'$id_plan',id_pallet,3,'$now','BEFORE LABEL' from ztb_assy_heating 
		 where id_print=$id_prin and position=2)";
	$data_ins = oci_parse($connect, $ins);
	oci_execute($data_ins);
	$pesan = oci_error($data_ins);
	$msg = $pesan['message'];
	if($msg != ''){
		$msg .= " Insert Query Error : $ins";
		break;
	}

	if($msg == ''){
		echo json_encode(array('successMsg'=>'BEFORE LABEL'));
	}else{
		echo json_encode(array('errorMsg'=>$msg));
	}
}
?>