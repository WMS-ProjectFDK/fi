<?php
$id = strval($_REQUEST['id']);
include("../../../connect/conn.php");

$sql = "delete from sp_mte_header where slip_no='$id'";
$result = sqlsrv_query($connect, $sql);

$del = "delete from sp_mte_details where slip_no='$id'";
$hasil = sqlsrv_query($connect, $del);

echo json_encode(array('success'=>true));	
?>