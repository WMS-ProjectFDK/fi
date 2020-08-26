<?php
session_start();
include("../../connect/conn.php");
if (isset($_SESSION['id_kanban'])) {
	$edit_cells = htmlspecialchars($_REQUEST['edit_cells']);
	$edit_tprod = htmlspecialchars($_REQUEST['edit_tprod']);
	$edit_mulai = htmlspecialchars($_REQUEST['edit_mulai']);
	$edit_akhir = htmlspecialchars($_REQUEST['edit_akhir']);
	$edit_qtbox = htmlspecialchars($_REQUEST['edit_qtbox']);
	$edit_prbox = htmlspecialchars($_REQUEST['edit_prbox']);
	$edit_tscan = htmlspecialchars($_REQUEST['edit_tscan']);
	$edit_idpln = htmlspecialchars($_REQUEST['edit_idpln']);

	$act_pallet = $edit_qtbox * $edit_prbox;

	$now=date('Y-m-d H:i:s');
	$user = $_SESSION['id_kanban'];
	$now2=date('Y-m-d');
	$id_kanban = $_SESSION['id_kanban'];
	$user_name = strtoupper($_SESSION['name_kanban']);
	
	$field .= "CELL_TYPE = '$edit_cells'," 									;
	$field .= "START_DATE = '$edit_mulai'," 								;
	$field .= "END_DATE = '$edit_akhir'," 									;
	$field .= "TANGGAL_PRODUKSI = convert(DATE, '$edit_tprod')," 			;
	$field .= "QTY_ACT_PERPALLET = $act_pallet,"							;
	$field .= "QTY_ACT_PERBOX = $edit_qtbox,"								;
	$field .= "TANGGAL_ACTUAL = CONVERT(DATE, '$edit_tscan') " 				;

	$upd = "update ztb_assy_kanban set $field where id = $edit_idpln";
	$data_upd = sqlsrv_query($connect, $upd);
	
	echo json_encode(array('successMsg'=>'success'));
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>