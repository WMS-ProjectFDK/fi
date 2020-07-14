<?php

$id = strval($_REQUEST['id']);

include("../connect/conn.php");

$sql = "delete from ztb_wh_mte_header where slip_no='$id'";
$result = oci_parse($connect, $sql);
oci_execute($result);
if ($result){
	$del = "delete from ztb_wh_mte_details where slip_no='$id'";
	$hasil = oci_parse($connect, $del);
	oci_execute($hasil);
	if($hasil){
		echo json_encode(array('success'=>true));	
	}else{
		echo json_encode(array('errorMsg'=>'Some errors occured.'));	
	}
}else{
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>