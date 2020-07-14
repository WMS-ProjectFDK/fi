<?php
date_default_timezone_set('Asia/Jakarta');
include("../../connect/conn.php");
$id_prin = isset($_REQUEST['id_prin']) ? strval($_REQUEST['id_prin']) : '';
$id_plan = isset($_REQUEST['id_plan']) ? strval($_REQUEST['id_plan']) : '';
$lblLine = isset($_REQUEST['lblLine']) ? strval($_REQUEST['lblLine']) : '';
$shift = isset($_REQUEST['shift']) ? strval($_REQUEST['shift']) : '';
$qty = isset($_REQUEST['qty']) ? strval($_REQUEST['qty']) : '';
$celltype = isset($_REQUEST['celltype']) ? strval($_REQUEST['celltype']) : '';

$label_lb = str_replace("-", "#", $lblLine);
$label_ln = str_replace("(T)", "", $label_lb);
$now=date('Y-m-d H:i:s');

if(! $connect){
	echo json_encode(array('errorMsg'=>'CONNECT TO SERVER FAILED ... !!'));
}else{
	$ins = "insert into ZTB_LBL_TRANS (id_print,qty,recorddate,labelline,shift,tanggal,asy_line,grade,lotdate,remark)
		select '$id_prin', '$qty', to_char(sysdate,'YYYY-MM-DD HH24:MI:SS'),'$label_ln','$shift',to_char(sysdate,'YYYY-MM-DD'),
		'-', '$celltype', '$id_plan', 'START' from dual";
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