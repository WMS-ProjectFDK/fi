<?php
session_start();
include("../connect/conn.php");
if (isset($_SESSION['id_wms'])){
	if($varConn == "Y"){
		$sql = "BEGIN ZSP_INSERT_DO(); END;";
		$stmt = oci_parse($connect, $sql);
		$res = oci_execute($stmt);	
	}else{
		echo json_encode(array('errorMsg'=>'Connection Failed'));
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>