<?php

$slip = strval($_REQUEST['slip']);
$item = strval($_REQUEST['item']);

include("../../../connect/conn.php");

$sql ="delete from sp_mte_details where slip_no='$slip' and item_no='$item' ";
$result = sqlsrv_query($connect, $sql);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>