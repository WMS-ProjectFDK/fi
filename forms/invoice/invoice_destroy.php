<?php
session_start();
$do_no = strval($_REQUEST['do_no']);
include("../../connect/conn.php");

if (isset($_SESSION['id_wms'])){
	if($varConn == "Y"){
		$sql = "delete from ZTB_DO_TEMP where do_no='$do_no' ";
		$data = sqlsrv_query($connect, strtoupper($sql));
		echo json_encode(array('successMsg'=>'success'));
	}else{
		echo json_encode(array('errorMsg'=>'Error'));
	}
}else{
	echo json_encode(array('errorMsg'=>'Error'));
}
?>