<?php
$di_no = strval($_REQUEST['di_no']);
include("../../connect/conn.php");

$sql = "delete from di_header where di_no='$di_no'";
$result = sqlsrv_query($connect, $sql);

if ($result){
	$del = "delete from di_details where di_no='$di_no'";
	$result_del = sqlsrv_query($connect, $del);
	echo json_encode(array('success'=>true));
}else{
	echo json_encode(array('errorMsg'=>'Some errors occured.'));	
}
?>