<?php
$gr = htmlspecialchars($_REQUEST['GR_NO']);
$line = htmlspecialchars($_REQUEST['LINE_NO']);
$pallet = htmlspecialchars($_REQUEST['PALLET']);
$qty = htmlspecialchars($_REQUEST['QTY']);
$rack = htmlspecialchars($_REQUEST['RACK']);
$item = htmlspecialchars($_REQUEST['ITEM_NO']);

include("../connect/conn.php");
$dt = date('Ymd');

$sql = "insert into ztb_wh_in_det (gr_no,line_no,qty,rack,item_no,pallet,tanggal) values ('$gr','$line','$qty','$rack','$item',$pallet,'$dt')";
$result = oci_parse($connect, $sql);
oci_execute($result);
if ($result){
	echo json_encode(array('Success'=>'Data Saved.'));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>