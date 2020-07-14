<?php
	header('Content-Type: text/plain; charset="UTF-8"');
	include("../../connect/conn.php");

	$id_print = isset($_REQUEST['id_print']) ? strval($_REQUEST['id_print']) : '';
	$labelline = isset($_REQUEST['labelline']) ? strval($_REQUEST['labelline']) : '';

	$del = "delete from ztb_lbl_trans_ng where id_print=$id_print and labelline=replace('$labelline','-','#')";
	$data_del = oci_parse($connect, $del);
	oci_execute($data_del);
	$pesan = oci_error($data_del);
	$msg = $pesan['message'];

	if($msg != ''){
		$msg .= " Delete Data error : $del";
		break;
	}

	if($msg == ''){
		echo json_encode('success');
	}else{
		echo json_encode($msg);
	}
?>