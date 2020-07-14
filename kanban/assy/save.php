<?php
session_start();
include("../../connect/conn.php");
if (isset($_SESSION['id_kanban'])) {
	$id = htmlspecialchars($_REQUEST['id']);
	$qty_act_perpallet = htmlspecialchars($_REQUEST['qty_act_perpallet']);
	$qty_act_perbox = htmlspecialchars($_REQUEST['qty_act_perbox']);
	$qty_box = htmlspecialchars($_REQUEST['qty_box']);
	$tgl_adh = htmlspecialchars($_REQUEST['tgl_adh']);
	$tgl_cca = htmlspecialchars($_REQUEST['tgl_cca']);
	$tgl_sep = htmlspecialchars($_REQUEST['tgl_sep']);
	$tgl_gel = htmlspecialchars($_REQUEST['tgl_gel']);
	$tgl_elektro = htmlspecialchars($_REQUEST['tgl_elektrolyte']);
	$txt_adh_paint = htmlspecialchars($_REQUEST['txt_adh_paint']);
	$txt_separator = htmlspecialchars($_REQUEST['txt_separator']);

	if($plan == 'QC'){
		$qty_act_perbox = ceil($qty_act_perpallet / $qty_box);
		$qty_act_perpallet = $qty_act_perpallet;
	}else{
		$qty_act_perpallet = $qty_act_perbox * $qty_box;	
	}

	if($txt_adh_paint == ''){
		$t_adh = ' ';
	}else{
		$t_adh = $txt_adh_paint;
	}

	if($txt_separator == ''){
		$t_sep = ' ';
	}else{
		$t_sep = $txt_separator;
	}

	$now=date('Y-m-d H:i:s');
	
	$field .= "END_DATE = to_char(sysdate, 'YYYY-MM-DD HH24:MI:SS')," 			;
	$field .= "QTY_ACT_PERPALLET = $qty_act_perpallet,"							;
	$field .= "QTY_ACT_PERBOX = $qty_act_perbox,"								;
	$field .= "NG_ID = 0,"						 								;
	$field .= "NG_QTY = 0,"							 							;
	$field .= "TANGGAL_ADHESIVE = TO_DATE('$tgl_adh','yyyy-mm-dd')," 			;
	$field .= "TANGGAL_CCA = TO_DATE('$tgl_cca','yyyy-mm-dd')," 				;
	$field .= "TANGGAL_SEPARATOR = TO_DATE('$tgl_sep','yyyy-mm-dd')," 			;
	$field .= "TANGGAL_GEL = TO_DATE('$tgl_gel','yyyy-mm-dd')," 				;
	$field .= "TANGGAL_ELECTROLYTE = TO_DATE('$tgl_elektro','yyyy-mm-dd')," 	;
	$field .= "COMM_ADHESIVE = '$t_adh',"				 						;
	$field .= "COMM_SEPARATOR = '$t_sep'" 										;

	$upd = "update ztb_assy_kanban set $field 
		where id = (select max(id) from ztb_assy_kanban where id_print = $id)";
	echo $upd;
	$data_upd = oci_parse($connect, $upd);
	oci_execute($data_upd);
	
	echo json_encode(array('successMsg'=>'SUCCESS'));
}else{
	echo json_encode(array('errorMsg'=>'SESSION EXPIRED'));
}
?>