<?php

$id = strval($_REQUEST['id']);
$qt_no = strval($_REQUEST['qt_no']);

include("../connect/conn2.php");

$sql = "delete from ztb_prf_quotation_detail_item where quotation_no='$qt_no' and item_no='$id' ";
$result = oci_parse($connect, $sql);
oci_execute($result);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>