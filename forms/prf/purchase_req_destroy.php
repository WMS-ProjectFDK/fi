<?php
$prf_no = strval($_REQUEST['prf_no']);
include("../connect/conn.php");
$msg = '';

$del = "delete from prf_header where prf_no='".$prf_no."'";
$data_del = oci_parse($connect, $del);
oci_execute($data_del);
$pesan = oci_error($data_del);
$msg .= $pesan['message'];

if($msg != ''){
	$msg .= " Delete-Header Process Error : $del";
	break;
}

$del2 = "delete from prf_details where prf_no='".$prf_no."'";
$data_del2 = oci_parse($connect, $del2);
oci_execute($data_del2);
$pesan = oci_error($data_del2);
$msg .= $pesan['message'];

if($msg != ''){
	$msg .= " Delete-Details Process Error : $del2";
	break;
}

$del3 = "delete from ztb_prf_sts where prf_no='".$prf_no."'";
$data_del3 = oci_parse($connect, $del3);
oci_execute($data_del3);
$pesan = oci_error($data_del3);
$msg .= $pesan['message'];

if($msg != ''){
	$msg .= " Delete-PRF Status Process Error : $del3";
	break;
}

if ($msg != ''){
	echo json_encode(array('errorMsg'=>$msg));
}else{
	echo json_encode(array('success'=>true));	
}
?>