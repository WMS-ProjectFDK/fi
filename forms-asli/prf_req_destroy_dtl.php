<?php

$id = strval($_REQUEST['idprf']);

include("../connect/conn2.php");

$sql ="delete from ztb_prf_req_details where id='$id'";
$result = oci_parse($connect, $sql);
oci_execute($result);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>