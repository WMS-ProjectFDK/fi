<?php
date_default_timezone_set("Asia/Bangkok");
session_start();
if (isset($_SESSION['id_wms'])) {
	$id  = htmlspecialchars($_REQUEST['id']); 
	$gr = htmlspecialchars($_REQUEST['GR_NO']);
	$line = htmlspecialchars($_REQUEST['LINE_NO']);
	$pallet = htmlspecialchars($_REQUEST['PALLET']);
	$qty = htmlspecialchars($_REQUEST['QTY']);
	$rack = htmlspecialchars($_REQUEST['RACK']);
	$item = htmlspecialchars($_REQUEST['ITEM_NO']);

	include("../connect/conn.php");
	$sql = "update ztb_wh_in_det set gr_no='$gr', line_no='$line', pallet=$pallet, qty=$qty, rack='$rack', item_no='$item' where id=$id";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	if ($data){
		echo json_encode($sql);
	} else {
		echo json_encode(array('errorMsg'=>$sql));
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>