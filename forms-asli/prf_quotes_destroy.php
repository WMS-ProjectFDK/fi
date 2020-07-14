<?php
$id = strval($_REQUEST['id']);
include("../connect/conn2.php");

$sql1 = "delete from ztb_prf_quotation_header where quotation_no='$id'";
$result1 = oci_parse($connect, $sql1);
oci_execute($result1);

if ($result1){
	$sql2 = "delete from ztb_prf_quotation_detail_item where quotation_no='$id'";
	$result = oci_parse($connect, $sql2);
	oci_execute($result);

	if($result){
		echo json_encode(array('successMsg'=>'Delete success!!'));	
	}else{
		echo json_encode(array('errorMsg'=>'Some errors occured.'));	
	}
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>