<?php
include("../connect/conn.php");
$item_no = strval($_REQUEST['item_no']);

$msg = '';

$del = "delete from ztb_safety_stock where item_no='".$item_no."' and period=0 and year='MSTR' ";
$data_del = oci_parse($connect, $del);
oci_execute($data_del);
$pesan = oci_error($data_del);
$msg .= $pesan['message'];

if($msg != ''){
	$msg .= " Delete Process Error : $del";
	break;
}

$sql = "BEGIN ZSP_SAFETY_STOCK_1(); END;";
$stmt = oci_parse($connect, $sql);
$res = oci_execute($stmt);
$pesan = oci_error($stmt);
$msg = $pesan['message'];

if($msg != ''){
	$msg .= "Proses Create Safety_stock Error";
	break;
}

if ($msg != ''){
	echo json_encode(array('errorMsg'=>$msg));
}else{
	echo json_encode(array('success'=>true));	
}
?>