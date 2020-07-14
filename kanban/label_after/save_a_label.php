<?php
date_default_timezone_set('Asia/Jakarta');
include("../../connect/conn.php");
$id_kanban = isset($_REQUEST['id_kanban']) ? strval($_REQUEST['id_kanban']) : '';
$id_worker = isset($_REQUEST['id_worker']) ? strval($_REQUEST['id_worker']) : '';

$now=date('Y-m-d H:i:s');

if(! $connect){
	echo json_encode(array('errorMsg'=>'CONNECT TO SERVER FAILED ... !!'));
}else{
	$ins = "insert into ztb_kanban_lbl(IDKANBAN,Worker_ID,startdate) values ($id_kanban,$id_worker,'$now')";
	$data_ins = oci_parse($connect, $ins);
	oci_execute($data_ins);
	$pesan = oci_error($data_ins);
	$msg = $pesan['message'];

	if($msg != ''){
		$msg .= " Insert Query Error : $ins";
		break;
	}

	if($msg == ''){
		echo json_encode(array('successMsg'=>'success'));
	}else{
		echo json_encode(array('errorMsg'=>$msg));
	}
}
?>