<?php
$gr = strval($_REQUEST['gr']);
$ln = strval($_REQUEST['ln']);
$item = strval($_REQUEST['item']);
include("../connect/conn.php");

$sql = "delete from ztb_wh_in_det where gr_no='$gr' and line_no='$ln' and item_no='$item' and rack is null";
$result = oci_parse($connect, $sql);
oci_execute($result);
if ($result){
	echo json_encode(array('success'=>true));
}else{
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>