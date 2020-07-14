<?php
session_start();
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
	$date_mulai = htmlspecialchars($_REQUEST['date_mulai']);
	$date_akhir = htmlspecialchars($_REQUEST['date_akhir']);

	$now=date('Y-m-d H:i:s');
	$user = $_SESSION['id_kanban'];
	$now2=date('Y-m-d');
	$id_kanban = $_SESSION['id_kanban'];
	$user_name = strtoupper($_SESSION['name_kanban']);

	//CEK GR_HEADERS
	/*$cek = "select count(*) as jum from ztb_assy_kanban 
			where assy_line='$assy_line' and cell_type='$cell_type' and pallet=$pallet and tanggal_produksi=to_date('$tgl_prod','yyyy-mm-dd') ";

	//echo $cek;
	$data = oci_parse($connect, $cek);
	oci_execute($data);
	$dt = oci_fetch_object($data);

	if(intval($dt->JUM) == 0){*/
		// insert ke ztb_assy_kanban
		$field  = "WORKER_ID1,"  		; 	$value  = "'$id_kanban',"						;
		$field .= "START_DATE,"  		; 	$value .= "'$date_mulai'," 						;
		$field .= "END_DATE,"  			; 	$value .= "'$date_akhir'," 						;
		$field .= "ASSY_LINE," 			; 	$value .= "'$assy_line',"						;
		$field .= "CELL_TYPE,"  		; 	$value .= "'$cell_type',"						;
		$field .= "PALLET,"  			; 	$value .= "$pallet,"							;
		$field .= "TANGGAL_PRODUKSI,"  	; 	$value .= "TO_DATE('$tgl_prod','yyyy-mm-dd')," 	;
		$field .= "QTY_PERPALLET," 		; 	$value .= "$qty_perpallet,"						;
		$field .= "QTY_ACT_PERPALLET,"  ; 	$value .= "$qty_act_perpallet,"					;
		$field .= "QTY_PERBOX,"  		; 	$value .= "$qty_perbox,"					 	;
		$field .= "QTY_ACT_PERBOX,"  	; 	$value .= "$qty_act_perbox,"					;
		$field .= "NG_ID_PROSES,"  		; 	$value .= "0,"									;
		$field .= "NG_ID," 				; 	$value .= "0,"						 			;
		$field .= "NG_QTY" 				; 	$value .= "0"							 		;
		chop($field);              		chop($value) ;

		$ins1  = "insert into ztb_assy_kanban ($field) VALUES ($value)" ;
		echo $ins1;
		$data_ins1 = oci_parse($connect, $ins1);
		oci_execute($data_ins1);
	/*}*/
	
	if ($data_ins1){
		echo json_encode(array('successMsg'=>'success'));
	}else{
		echo json_encode(array('errorMsg'=>'Error'));
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>