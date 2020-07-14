<?php
session_start();
include("../../connect/conn_kanbansys.php");
if (isset($_SESSION['id_kanban'])) {
	$ID = htmlspecialchars($_REQUEST['ID']);

	$del = "delete from ztb_assy_kanban where id=$ID";
	$data_del = odbc_exec($connect, $del);
	odbc_execute($data_del);
	echo json_encode(array('successMsg'=>'success'));
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>