<?php
session_start();
include("../../connect/conn_kanbansys.php");
if (isset($_SESSION['id_kanban'])){
	if(! $connect){
		echo json_encode(array('errorMsg'=>'CONNECT TO SERVER FAILED ... !!'));
	}else{
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
		
		$field .= "END_DATE = convert(varchar,GETDATE(),120)," 				;
		$field .= "QTY_ACT_PERPALLET = $qty_act_perpallet,"					;
		$field .= "QTY_ACT_PERBOX = $qty_act_perbox,"						;
		$field .= "NG_ID = 0,"						 						;
		$field .= "NG_QTY = 0,"							 					;
		$field .= "TANGGAL_ADHESIVE = CONVERT(DATE,'$tgl_adh')," 			;
		$field .= "TANGGAL_CCA = CONVERT(DATE,'$tgl_cca')," 				;
		$field .= "TANGGAL_SEPARATOR = CONVERT(DATE,'$tgl_sep')," 			;
		$field .= "TANGGAL_GEL = CONVERT(DATE,'$tgl_gel')," 				;
		$field .= "TANGGAL_ELECTROLYTE = CONVERT(DATE,'$tgl_elektro')," 	;
		$field .= "COMM_ADHESIVE = '$t_adh',"				 				;
		$field .= "COMM_SEPARATOR = '$t_sep'" 								;

		$upd = "update ztb_assy_kanban set $field 
			where id = (select max(id) from ztb_assy_kanban where id_print = $id)";
		echo $upd;
		$data_upd = odbc_exec($connect, $upd);
		
		echo json_encode(array('successMsg'=>'SUCCESS'));
	}
}else{
	echo json_encode(array('errorMsg'=>'SESSION EXPIRED'));
}
?>