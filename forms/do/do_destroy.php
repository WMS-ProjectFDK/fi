<?php
$id = strval($_REQUEST['id']);
include("../../connect/conn.php");

$sql = "delete from mte_header where slip_no='$id'";
$result = sqlsrv_query($connect, $sql);

$del = "delete from mte_details where slip_no='$id'";
	$hasil = sqlsrv_query($connect, $del);

	echo json_encode(array('success'=>true));	

?>