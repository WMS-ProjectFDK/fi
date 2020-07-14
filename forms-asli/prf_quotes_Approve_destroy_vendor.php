<?php

$id = strval($_REQUEST['id']);
$qt_no = strval($_REQUEST['qt_no']);
$vndor = strval($_REQUEST['vndor']);

include("../connect/conn2.php");

$sql = "delete from ZTB_PRF_QUOTATION_DETAIL_COMP where quotation_no='$qt_no' and item_no='$id' and vendor='$vndor'";
$result = oci_parse($connect, $sql);
oci_execute($result);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>