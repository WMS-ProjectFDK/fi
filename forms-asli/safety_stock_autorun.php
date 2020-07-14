<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../connect/conn.php");
$msg = '';

$sql = "BEGIN ZSP_SAFETY_STOCK_1(); END;";
$stmt = oci_parse($connect, $sql);
$res = oci_execute($stmt);
$pesan = oci_error($stmt);
$msg .= $pesan['message'];

if($msg != ''){
    $msg .= "Procedure Run Error  : $res";
    echo json_encode($msg);
}else{
    echo json_encode("success");
}
?>