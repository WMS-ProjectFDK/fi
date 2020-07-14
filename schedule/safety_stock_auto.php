<?php
set_time_limit(0);
include("../connect/conn.php");

$sql = "BEGIN ZSP_SAFETY_STOCK_1(); END;";
$stmt = oci_parse($connect, $sql);
$res = oci_execute($stmt);
$pesan = oci_error($stmt);
$msg = $pesan['message'];

if($msg == ''){
	$arrData[$arrNo] = array("Proses Create Safety_stock success"=>'SUCCESS');
}else{
	$arrData[$arrNo] = array("kode"=>$msg);
}

?>