<?php
$di_no = strval($_REQUEST['di_no']);
include("../connect/conn.php");

$sql = "delete from di_header where di_no='$di_no'";
$result = oci_parse($connect, $sql);
oci_execute($result);

if ($result){
	$del = "delete from di_details where di_no='$di_no'";
	$result_del = oci_parse($connect, $del);
	oci_execute($result_del);
	echo json_encode(array('success'=>true));
}else{
	echo json_encode(array('errorMsg'=>'Some errors occured.'));	
}
?>