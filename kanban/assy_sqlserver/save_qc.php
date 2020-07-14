<?php
session_start();
include("../../connect/conn_kanbansys.php");
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

	$now=date('Y-m-d H:i:s');
	$user = $_SESSION['id_kanban'];
	$now2=date('Y-m-d');
	$id_kanban = $_SESSION['id_kanban'];
	$user_name = strtoupper($_SESSION['name_kanban']);
	
	// insert ke ztb_assy_kanban
	$field .= "WORKER_ID1,"  			; 	$value .= "'111',"							;
	$field .= "ID_PLAN,"  			    ; 	$value .= "'QC',"							;
	$field .= "START_DATE,"  			; 	$value .= "'$now'," 						;
	$field .= "END_DATE,"				; 	$value .= "'$now',"							;
	$field .= "ASSY_LINE," 				; 	$value .= "'$assy_line',"					;
	$field .= "CELL_TYPE,"  			; 	$value .= "'$cell_type',"					;
	$field .= "PALLET,"  				; 	$value .= "0,"								;
	$field .= "TANGGAL_PRODUKSI,"  		; 	$value .= "CONVERT(DATE, '$tgl_prod')," 	;
	$field .= "QTY_PERPALLET," 			; 	$value .= "$qty_perpallet,"					;
	$field .= "QTY_ACT_PERPALLET,"		; 	$value .= "$qty_act_perpallet,"				;
	$field .= "QTY_PERBOX,"  			; 	$value .= "$qty_perbox,"					;
	$field .= "QTY_ACT_PERBOX,"			; 	$value .= "$qty_act_perbox,"				;
	$field .= "NG_ID_PROSES,"			; 	$value .= "0,"								;
	$field .= "NG_ID,"					; 	$value .= "0,"								;
	$field .= "NG_QTY,"					; 	$value .= "0,"								;
	$field .= "TANGGAL_ACTUAL"  		; 	$value .= "CONVERT(DATE, '$tgl_prod')" 		;
	chop($field);              			chop($value) ;

	$ins  = "insert into ztb_assy_kanban ($field) select $value from dual 
		WHERE not exists (select * from ztb_assy_kanban where id_plan='QC' AND pallet = $pallet AND END_DATE is null AND assy_line='$assy_line')" ;
	echo $ins;
	$data_ins = oDBc_exec($connect, $ins);
	
	if ($data_ins){
		echo json_encode(array('successMsg'=>'success'));
	}else{
		echo json_encode(array('errorMsg'=>'Error'));
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>