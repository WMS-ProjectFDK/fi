<?php
session_start();
if (isset($_SESSION['id_kanban'])){
	include("../../connect/conn.php");

	$perbaikan_no = htmlspecialchars($_REQUEST['perbaikan_no']);
	$perbaikan_commenet = htmlspecialchars($_REQUEST['perbaikan_commenet']);

	$sql = "update ztb_assy_trans_ng set perbaikan='$perbaikan_commenet' where ng_no='$perbaikan_no' ";
	// echo $sql;
	$data = sqlsrv_query($connect, $sql);
	echo json_encode(array('successMsg'=>'Succes'));
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>