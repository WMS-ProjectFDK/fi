<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn2.php");
	
	$qta3 = htmlspecialchars($_REQUEST['qta3']);
	$itm3 = htmlspecialchars($_REQUEST['itm3']);
	$vndr = htmlspecialchars($_REQUEST['vndr']);
	$pric = htmlspecialchars($_REQUEST['pric']);

	$now=date('Y-m-d H:i:s');
	$user = $_SESSION['id_wms'];
	$id0=date('Ymd');

	$cek = "select count(*) from ztb_prf_quotation_detail_comp where quotation_no='$qta3' and item_no='$itm3' and vendor='$vndr'";
	$data = oci_parse($connect, $cek);
	oci_execute($data);
	$dt = oci_fetch_array($data);

	if($dt[0]==0){
		$ins1 = "insert into ztb_prf_quotation_detail_comp (quotation_no,item_no,vendor,price,flag_approved) values ('$qta3','$itm3', '$vndr', $pric, 0)";
		$data_ins1 = oci_parse($connect, $ins1);
		oci_execute($data_ins1);
	}

	if ($data_ins1){
        echo json_encode(array('succesMsg' =>'Success'));
    }else{
        echo json_encode(array('errorMsg'=>'Error'));
    }
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>