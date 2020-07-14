<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn.php");
	$dn_no = htmlspecialchars($_REQUEST['dn_no']);
	$dn_qt = htmlspecialchars($_REQUEST['dn_qt']);

	$cek_jum = "update ztb_wh_daily_needs set QTY_NEEDS=$dn_qt where ID_NEEDS ='$dn_no' ";
	$data_jum = oci_parse($connect, $cek_jum);
	oci_execute($data_jum);

	if ($data_jum) {
		echo json_encode(array('successMsg'=>$dn_no));
	}else{
		echo json_encode(array('errorMsg'=>'Data Error'));
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>