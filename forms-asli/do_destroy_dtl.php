<?php

$slip = strval($_REQUEST['slip']);
$item = strval($_REQUEST['item']);

include("../connect/conn.php");

$sql ="delete from mte_details where slip_no='$slip' and item_no=$item";
$result = oci_parse($connect, $sql);
oci_execute($result);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>