<?php
session_start();
include("../../connect/conn.php");
if (isset($_SESSION['id_kanban'])) {
	$ID = htmlspecialchars($_REQUEST['ID']);

	$del = "delete from ztb_assy_kanban where id=$ID";
	echo $del;
	$data_upd_s = oci_parse($connect, $upd_s);
	oci_execute($data_upd_s);
	echo json_encode(array('successMsg'=>'success'));
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>