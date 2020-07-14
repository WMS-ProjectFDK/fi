<?php
session_start();
if (isset($_SESSION['id_wms'])){
	
	$bln = htmlspecialchars($_REQUEST['bln']);
	$thn = htmlspecialchars($_REQUEST['thn']);
	$idr = htmlspecialchars($_REQUEST['idr']);
	$jpy = htmlspecialchars($_REQUEST['jpy']);
	$sgd = htmlspecialchars($_REQUEST['sgd']);
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

	$cek = "select count(*) as jum from ztb_prf_parameter where doc_no='$doc' and departement='$dpt'";
	$data = oci_parse($connect, $cek);
	oci_execute($data);
	$row = oci_fetch_array($data);

	if($row[0] == 0){
		$sql = "insert into ztb_prf_parameter values ('$doc','$dpt',$bdg,$idr,$jpy,$sgd,0,0,'$idp')";
		$data = oci_parse($connect, $sql);
		oci_execute($data);		
	}else{
		echo json_encode(array('errorMsg'=>'Data Already exist ..'));
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>