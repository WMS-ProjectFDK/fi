<?php
$id = strval($_REQUEST['id']);
include("../../connect/conn.php");

$del = "update item set delete_type='D' where item_no='$id'";
$result = sqlsrv_query($connect, $del);

echo json_encode(array('success'=>true));
?>