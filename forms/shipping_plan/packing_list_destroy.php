<?php
$id = strval($_REQUEST['id']);
include("../../connect/conn.php");

$sql = "delete from ztb_shipping_ins where answer_no='$id'";
$result = sqlsrv_query($connect, $sql);

if ($result){
	echo json_encode(array('success'=>true));
}else{
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>