<?php
session_start();
if (isset($_SESSION['id_wms'])){
	$kode = htmlspecialchars($_REQUEST['kode']);

	include("../connect/conn.php");

	$sql = "delete from ztb_menu where id='$kode'";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	if ($result){
		echo json_encode(array('success'=>true));
	} else {
		echo json_encode(array('errorMsg'=>'Some errors occured.'));
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>