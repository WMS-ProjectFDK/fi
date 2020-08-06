<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
ini_set('max_execution_time', -1);
include("../../connect/conn.php");
$user_name = $_SESSION['id_wms'];
$ppbe_no = isset($_REQUEST['ppbe_no']) ? strval($_REQUEST['ppbe_no']) : '';
$total = 0;

if (isset($_SESSION['id_wms'])){

	$sql = "select count(*) JUM from zvw_pallet_check 
	where  (gross_detail - gross_ins  not between -1 and 1 
		  or gross_detail - gross_inv  not between -1 and 1 
		  or gross_inv - gross_ins  not between -1 and 1
		  or msm_detail - msm_ins  not between -1 and 1 
		  or msm_detail - msm_inv  not between -1 and 1
		  or msm_inv - msm_ins  not between -1 and 1
		  or net_detail - net_ins  not between -1 and 1 
		  or net_detail - net_inv  not between -1 and 1
		  or net_inv - net_ins  not between -1 and 1)
		  and ppbe_no = '$ppbe_no'";
	$data = sqlsrv_query($connect, strtoupper($sql));
	$row = sqlsrv_fetch_object($data);
	
	if (intval($row->JUM) != 0 ){
		$msg = ("Perhitungan MSM dan Gross ada yang berbeda, silahkan kalkulasi ulang. Jika masih terjadi silahkan hubungi IT.");
	}
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
	echo $msg;
}else{
	echo  json_encode('success');
}
?>