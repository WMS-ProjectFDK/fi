<?php
$id = intval($_REQUEST['id']);
include("../connect/conn.php");

$sql = "update ztb_wh_in_det set rack='', qty_out=qty, qty_reserve=qty where id=$id";
$result = oci_parse($connect, $sql);
oci_execute($result);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>