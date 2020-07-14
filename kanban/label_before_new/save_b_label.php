	<?php
date_default_timezone_set('Asia/Jakarta');
include("../../connect/conn.php");
$id_prin = isset($_REQUEST['id_prin']) ? strval($_REQUEST['id_prin']) : '';
$id_plan = isset($_REQUEST['id_plan']) ? strval($_REQUEST['id_plan']) : '';
$lblLine = isset($_REQUEST['lblLine']) ? strval($_REQUEST['lblLine']) : '';
// $lblNo = isset($_REQUEST['lblNo']) ? strval($_REQUEST['lblNo']) : '';
$shift = isset($_REQUEST['shift']) ? strval($_REQUEST['shift']) : '';

$label_lb = str_replace("-", "#", $lblLine);
$label_ln = str_replace("(T)", "", $label_lb);
$now=date('Y-m-d H:i:s');

if(! $connect){
	echo json_encode(array('errorMsg'=>'CONNECT TO SERVER FAILED ... !!'));
}else{
	$cekPos = "select max(position) as max from ztb_assy_heating where id_print=$id_prin";
	$data_cekPos = oci_parse($connect, $cekPos);
	oci_execute($data_cekPos);
	$rowCekPos = oci_fetch_object($data_cekPos);

	if($rowCekPos->MAX == 2){
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
	}
	
	$ins2 = "insert into ZTB_LBL_TRANS (id_print,qty,recorddate,labelline,shift,tanggal,asy_line,grade,lotdate,remark)
		select id_print, sum(qty_act_perpallet), to_char(sysdate,'YYYY-MM-DD HH24:MI:SS'),'$label_ln','$shift',to_char(sysdate,'YYYY-MM-DD'),
		assy_line, cell_type, tanggal_produksi, 'START'
		from ztb_assy_kanban
		where id_print=$id_prin
		group by id_print,assy_line, cell_type, tanggal_produksi";
	$data_ins2 = oci_parse($connect, $ins2);
	oci_execute($data_ins2);
	$pesan = oci_error($data_ins2);
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