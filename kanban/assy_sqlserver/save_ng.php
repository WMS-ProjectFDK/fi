<?php
session_start();
include("../../connect/conn_kanbansys.php");
if (isset($_SESSION['id_kanban'])) {

	$assy_line = htmlspecialchars($_REQUEST['assy_line']);
	$cell_type = htmlspecialchars($_REQUEST['cell_type']);
	$pallet = htmlspecialchars($_REQUEST['pallet']);
	$tgl_prod = htmlspecialchars($_REQUEST['tgl_prod']);
	$ng_proses = htmlspecialchars($_REQUEST['ng_proses']);
	$ng_name = htmlspecialchars($_REQUEST['ng_name']);
	$ng_qty = htmlspecialchars($_REQUEST['ng_qty']);
	$plan = htmlspecialchars($_REQUEST['plan']);
	$id = htmlspecialchars($_REQUEST['id']);

	$now=date('Y-m-d H:i:s');
	$user = $_SESSION['id_kanban'];
	$now2=date('Y-m-d');
	$id_kanban = $_SESSION['id_kanban'];
	$user_name = strtoupper($_SESSION['name_kanban']);

	$kode= "select 'NG' +
					FORMAT(GETDATE(), 'yyMMdd') +
					coalesce(right(replicate('0',5)+cast(cast(max(substring(ng_no,9,5))as integer)+1 as varchar(5)),5), '00001') as ng_no
			from ztb_assy_trans_ng
			where substring(ng_no,3,6) = FORMAT(GETDATE(), 'yyMMdd')";
	$data_kd = odbc_exec($connect, $kode);
	$dt_kode = odbc_fetch_object($data_kd);
	$code = $dt_kode->ng_no;

	// insert ke ztb_assy_kanban
	$field .= "ASSY_LINE," 			; 	$value .= "'$assy_line',"						;
	$field .= "CELL_TYPE,"  		; 	$value .= "'$cell_type',"						;
	$field .= "PALLET,"  			; 	$value .= "$pallet,"							;
	$field .= "TANGGAL_PRODUKSI,"  	; 	$value .= "CONVERT(DATE, '$tgl_prod')," 		;
	$field .= "NG_ID_PROSES,"  		; 	$value .= "$ng_proses,"							;
	$field .= "NG_ID," 				; 	$value .= "$ng_name,"						 	;
	$field .= "NG_QTY," 			; 	$value .= "$ng_qty,"							;
	$field .= "WORKER_ID," 			; 	$value .= "'$id_kanban',"						;
	$field .= "NG_NO," 				; 	$value .= "'$code',"							;
	$field .= "ID_PRINT," 			; 	$value .= "$id,"								;
	$field .= "ID_PLAN" 			; 	$value .= "'$plan'"								;
	chop($field);              		chop($value) ;
  
	$ins1  = "insert into ztb_assy_trans_ng ($field) VALUES ($value)" ;
	echo $ins1;
	$data_ins1 = odbc_exec($connect, $ins1);
	
	if ($data_ins1){
		echo json_encode(array('successMsg'=>'success'));
	}else{
		echo json_encode(array('errorMsg'=>'Error'));
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>