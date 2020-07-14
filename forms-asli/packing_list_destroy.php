<?php
$id = strval($_REQUEST['id']);
include("../connect/conn.php");

$sql = "delete from ztb_shipping_ins where rowid='$id'";
$result = oci_parse($connect, $sql);
oci_execute($result);

if ($result){
	echo json_encode(array('success'=>true));
}else{
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>