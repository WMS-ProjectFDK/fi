<?php
session_start();
include("../../connect/conn.php");
if (isset($_SESSION['id_kanban'])) {
	$cell_type = htmlspecialchars($_REQUEST['cell_type']);
	$pallet = htmlspecialchars($_REQUEST['pallet']);
	$tgl_prod = htmlspecialchars($_REQUEST['tgl_prod']);
	$id = htmlspecialchars($_REQUEST['id']);

	$id_kanban = $_SESSION['id_kanban'];
	// insert ke ztb_assy_kanban
	$field2 .= "WORKER_ID1,"  			; 	$value2 .= "'$id_kanban',"								;
	$field2 .= "ID_PLAN,"  			    ; 	$value2 .= "id_plan,"									;
	$field2 .= "START_DATE,"  			; 	$value2 .= "to_char(sysdate, 'YYYY-MM-DD HH24:MI:SS')," ;
	$field2 .= "ASSY_LINE," 			; 	$value2 .= "asyline,"									;
	$field2 .= "CELL_TYPE,"  			; 	$value2 .= "'$cell_type',"								;
	$field2 .= "PALLET,"  				; 	$value2 .= "'$pallet',"									;
	$field2 .= "TANGGAL_PRODUKSI,"  	; 	$value2 .= "TO_DATE('$tgl_prod','yyyy-mm-dd')," 		;
	$field2 .= "TANGGAL_ACTUAL,"  		; 	$value2 .= "TO_DATE('$tgl_prod','yyyy-mm-dd')," 		;
	$field2 .= "QTY_PERPALLET," 		; 	$value2 .= "qty,"										;
	$field2 .= "ID_PRINT," 				; 	$value2 .= "id,"										;
	$field2 .= "QTY_PERBOX"  			; 	$value2 .= "box"					 					;
	chop($field2);              		chop($value2) ;

	$ins2  = "insert into ztb_assy_kanban ($field2) select $value2 from ztb_assy_print WHERE id = $id";
	echo $ins2.'<br/>';
	$data_ins2 = oci_parse($connect, $ins2);
	oci_execute($data_ins2);

	$updprint = "update ztb_assy_print set printed = 1 where id= $idNya ";
	$data_updprint = oci_parse($connect, $updprint);
	oci_execute($data_updprint);

	echo json_encode(array('successMsg'=>'SUCCESS'));
}else{
	echo json_encode(array('errorMsg'=>'SESSION EXPIRED'));
}
?>