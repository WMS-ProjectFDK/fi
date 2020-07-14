<?php
set_time_limit(0);
include("../connect/conn.php");
date_default_timezone_set('Asia/Jakarta');
$arrData = array();
$arrNo = 0;
$msg = '';

echo date("Y-m-d H:i:s").' - START<br/>';
$msg = '';
$sql = "BEGIN ZSP_SAFETY_STOCK_1(); END;";
$stmt = oci_parse($connect, $sql);
$res = oci_execute($stmt);
$pesan = oci_error($stmt);
$msg = $pesan['message'];

if($msg == ''){
	$arrData[$arrNo] = array("Proses Insert Calculation MPS"=>'SUCCESS');
}else{
	$arrData[$arrNo] = array("kode1"=>$msg);
}
?>