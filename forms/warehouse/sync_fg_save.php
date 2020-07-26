<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn.php");

	$qry = "update ztb_wh_kanban_trans_fg set flag = 1 where flag = 0 ";
	$result = oci_parse($connect, $qry);
  	oci_execute($result);
  	
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>