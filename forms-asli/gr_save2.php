<?php
session_start();
include("../connect/conn.php");
$arrData = array();
$arrNo = 0;

if (isset($_SESSION['id_wms'])){
	$msg = '';
	$user = $_SESSION['id_wms'];
	$sql = "BEGIN ZSP_INSERT_GR(:V_USER); END;";
	$stmt = oci_parse($connect, $sql);
	oci_bind_by_name($stmt, ':V_USER' , trim($user));
	$res = oci_execute($stmt);
	$pesan = oci_error($stmt);
	$msg = $pesan['message'];

	if($msg == ''){
		$arrData[$arrNo] = array("kode1"=>'SUCCESS');
	}else{
		$arrData[$arrNo] = array("kode1"=>$msg);
	}	
}else{
	$arrData[$arrNo] = array("kode1"=>'Session Expired');
}

echo json_encode($arrData);
?>