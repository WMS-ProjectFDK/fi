<?php

$id = intval($_REQUEST['id']);

include("../connect/conn2.php");

$sql = "delete from ztb_prf_parameter where doc_no='$id'";
$result = oci_parse($connect, $sql);
oci_execute($result);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>