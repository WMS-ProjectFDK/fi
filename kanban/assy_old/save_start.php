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
	$qty_perbox = htmlspecialchars($_REQUEST['qty_perbox']);
	$date_mulai = htmlspecialchars($_REQUEST['date_mulai']);
	$plan = htmlspecialchars($_REQUEST['plan']);
	$id = htmlspecialchars($_REQUEST['id']);

	if($id == ''){
		$idNya = 0;
	}else{
		$idNya = $id;
	}

	$now=date('Y-m-d H:i:s');
	$user = $_SESSION['id_kanban'];
	$now2=date('Y-m-d');
	$id_kanban = $_SESSION['id_kanban'];
	$user_name = strtoupper($_SESSION['name_kanban']);

	// insert ke ztb_assy_kanban_start
	$field 	= "ID," 				; 	$value  = "to_char(sysdate,'yyyymmddHH24MISS')," ;
	$field .= "WORKER_ID,"  		; 	$value .= "'$id_kanban',"						;
	$field .= "START_DATE,"  		; 	$value .= "'$now'," 							;
	$field .= "ASSY_LINE," 			; 	$value .= "'$assy_line',"						;
	$field .= "CELL_TYPE,"  		; 	$value .= "'$cell_type',"						;
	$field .= "PALLET,"  			; 	$value .= "$pallet,"							;
	$field .= "TANGGAL_PRODUKSI,"  	; 	$value .= "TO_DATE('$tgl_prod','yyyy-mm-dd')," 	;
	$field .= "QTY_PERPALLET," 		; 	$value .= "$qty_perpallet,"						;
	$field .= "QTY_PERBOX,"  		; 	$value .= "$qty_perbox,"					 	;
	$field .= "ID_PLAN,"  			; 	$value .= "'$plan',"					 		;
	$field .= "FLAG_END_TIME"  		; 	$value .= "0"					 				;
	chop($field);              		chop($value) ;

	$ins1  = "insert into ztb_assy_kanban_start ($field) select $value from dual
		WHERE not exists (select * from ztb_assy_kanban_start where id_plan='$plan' AND pallet = $pallet AND FLAG_END_TIME=0 AND assy_line='$assy_line')" ;
	echo $ins1;
	$data_ins1 = oci_parse($connect, $ins1);
	oci_execute($data_ins1);

	// insert ke ztb_assy_kanban
	$field2 .= "WORKER_ID1,"  			; 	$value2 .= "'$id_kanban',"							;
	$field2 .= "ID_PLAN,"  			    ; 	$value2 .= "'$plan',"								;
	$field2 .= "START_DATE,"  			; 	$value2 .= "'$now'," 								;
	$field2 .= "ASSY_LINE," 			; 	$value2 .= "'$assy_line',"							;
	$field2 .= "CELL_TYPE,"  			; 	$value2 .= "'$cell_type',"							;
	$field2 .= "PALLET,"  				; 	$value2 .= "$pallet,"								;
	$field2 .= "TANGGAL_PRODUKSI,"  	; 	$value2 .= "TO_DATE('$tgl_prod','yyyy-mm-dd')," 	;
	$field2 .= "TANGGAL_ACTUAL,"  		; 	$value2 .= "TO_DATE('$tgl_prod','yyyy-mm-dd')," 	;
	$field2 .= "QTY_PERPALLET," 		; 	$value2 .= "$qty_perpallet,"						;
	$field2 .= "ID_PRINT," 				; 	$value2 .= "$idNya,"								;
	$field2 .= "QTY_PERBOX"  			; 	$value2 .= "$qty_perbox"					 		;
	chop($field2);              		chop($value2) ;

	$ins2  = "insert into ztb_assy_kanban ($field2) select $value2 from dual 
		WHERE not exists (select * from ztb_assy_kanban where id_plan='$plan' AND pallet = $pallet AND END_DATE is null AND assy_line='$assy_line')" ;
	echo $ins2;
	$data_ins2 = oci_parse($connect, $ins2);
	oci_execute($data_ins2);

	$updprint = "update ztb_assy_print set printed = 1 where id= $idNya ";
	$data_updprint = oci_parse($connect, $updprint);
	oci_execute($data_updprint);

	echo json_encode(array('successMsg'=>'success'));
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>