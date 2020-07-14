<?php
session_start();
if (isset($_SESSION['id_wms'])){
	
	$bln = htmlspecialchars($_REQUEST['bln']);
	$thn = htmlspecialchars($_REQUEST['thn']);
	/*$idr = htmlspecialchars($_REQUEST['idr']);
	$jpy = htmlspecialchars($_REQUEST['jpy']);
	$sgd = htmlspecialchars($_REQUEST['sgd']);*/
	$idp = htmlspecialchars($_REQUEST['idp']);
	$dpt = htmlspecialchars($_REQUEST['dpt']);
	$bdg = htmlspecialchars($_REQUEST['bdg']);

	$doc = $thn.$bln;

	include("../connect/conn2.php");

	if($bdg == ''){
		$bdgt = 0;
	}else{
		$bdgt = $bdg;
	}

	
	$sql = "update ztb_prf_parameter set budget=$bdgt where doc_no='$doc' and departement='$dpt' and id_dept='$idp'";	//idr_rate=$idr, jpy_rate=$jpy, sgd_rate=$sgd
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	if($data){
		echo json_encode(array('errorMsg'=>'success'));	
	}else{
		echo json_encode(array('errorMsg'=>'error'));
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>