<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
include("../../connect/conn.php");
if (isset($_SESSION['id_kanban'])) {
	$assy_line = htmlspecialchars($_REQUEST['assy_line']);
	$cell_type = htmlspecialchars($_REQUEST['cell_type']);
	$pallet = htmlspecialchars($_REQUEST['pallet']);
	$tgl_prod = htmlspecialchars($_REQUEST['tgl_prod']);
	$qty_perpallet = htmlspecialchars($_REQUEST['qty_perpallet']);
	$qty_act_perpallet = htmlspecialchars($_REQUEST['qty_act_perpallet']);
	$qty_perbox = htmlspecialchars($_REQUEST['qty_perbox']);
	$qty_act_perbox = htmlspecialchars($_REQUEST['qty_act_perbox']);
	$qty_box = htmlspecialchars($_REQUEST['qty_box']);
	$date_mulai = htmlspecialchars($_REQUEST['date_mulai']);
	$date_akhir = htmlspecialchars($_REQUEST['date_akhir']);
	$tgl_adh = htmlspecialchars($_REQUEST['tgl_adh']);
	$tgl_cca = htmlspecialchars($_REQUEST['tgl_cca']);
	$tgl_sep = htmlspecialchars($_REQUEST['tgl_sep']);
	$tgl_gel = htmlspecialchars($_REQUEST['tgl_gel']);
	$tgl_elektro = htmlspecialchars($_REQUEST['tgl_elektrolyte']);
	$txt_adh_paint = htmlspecialchars($_REQUEST['txt_adh_paint']);
	$txt_separator = htmlspecialchars($_REQUEST['txt_separator']);
	$plan = htmlspecialchars($_REQUEST['plan']);
	$id = htmlspecialchars($_REQUEST['id']);

	if($plan == 'QC'){
		$qty_act_perbox = ceil($qty_act_perpallet / $qty_box);
		$qty_act_perpallet = $qty_act_perpallet;
	}else{
		$qty_act_perpallet = $qty_act_perbox * $qty_box;	
	}

	if($txt_adh_paint==''){
		$t_adh = ' ';
	}else{
		$t_adh = $txt_adh_paint;
	}

	if($txt_separator==''){
		$t_sep = ' ';
	}else{
		$t_sep = $txt_separator;
	}

	$now=date('Y-m-d H:i:s');
	$user = $_SESSION['id_kanban'];
	$now2=date('Y-m-d');
	$id_kanban = $_SESSION['id_kanban'];
	$user_name = strtoupper($_SESSION['name_kanban']);
	
	$field .= "END_DATE = '$date_akhir'," 										;
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
		where id = (select max(id) from ztb_assy_kanban where worker_id1='$id_kanban')";
	echo $upd;
	$data_upd = oci_parse($connect, $upd);
	oci_execute($data_upd);
	
	if ($data_upd){
		$upd_s = "update ztb_assy_kanban_start set flag_end_time = 1
			where id_plan = '$plan' AND PALLET = $pallet AND FLAG_END_TIME = 0";
		echo $upd_s;
		$data_upd_s = oci_parse($connect, $upd_s);
		oci_execute($data_upd_s);

		/*$updprint = "update ztb_assy_print set printed = 1 where id= $id ";
		$data_updprint = oci_parse($connect, $updprint);
		oci_execute($data_updprint);*/

		echo json_encode(array('successMsg'=>'success'));
	}else{
		echo json_encode(array('errorMsg'=>'Error'));
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>