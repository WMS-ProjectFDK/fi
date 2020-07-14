<?php
$po_no = strval($_REQUEST['po_no']);
include("../connect/conn.php");

$del = "delete from po_header where po_no='".$po_no."'";
$data_del = oci_parse($connect, $del);
oci_execute($data_del);

if($data_del){
	$cek = "select item_no, qty, prf_no, prf_line_no from po_details where po_no='".$po_no."'";
	$data_cek = oci_parse($connect, $cek);
	oci_execute($data_cek);
	while ($dt_cek = oci_fetch_object($data_cek)) {
		$upd = "update PRF_DETAILS
			    set REMAINDER_QTY = REMAINDER_QTY + (".$dt_cek->QTY.")
			    , UPTO_DATE = sysdate
			 	where PRF_NO = '".$dt_cek->PRF_NO."'
			   	and LINE_NO = ".$dt_cek->PRF_LINE_NO."";
		$data_upd = oci_parse($connect, $upd);
		oci_execute($data_upd);	
	}

	$del2 = "delete from po_details where po_no='".$po_no."'";
	$data_del2 = oci_parse($connect, $del2);
	oci_execute($data_del2);
	
	if($data_del2){
		echo json_encode(array('success'=>true));
	}else{
		echo json_encode(array('errorMsg'=>'Some errors occured.'));
	}
}else{
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>