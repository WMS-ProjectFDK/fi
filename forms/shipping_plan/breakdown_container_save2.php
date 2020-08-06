<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../../connect/conn.php");
	
	$bdc_tw = htmlspecialchars($_REQUEST['bdc_tw']);
	$bdc_enr = htmlspecialchars($_REQUEST['bdc_enr']);
	$bdc_ppbe = htmlspecialchars($_REQUEST['bdc_ppbe']);
	$bdc_size = htmlspecialchars($_REQUEST['bdc_size']);
	$bdc_container = htmlspecialchars($_REQUEST['bdc_container']);
	$bdc_row = htmlspecialchars($_REQUEST['bdc_row']);
	
	if ($bdc_container != 'TOTAL'){
		$sql = "update ztb_shipping_detail set containers = '".$bdc_size ."' , TW = '". $bdc_tw ."', ENR = '". $bdc_enr ."', CONTAINER_NO = '". $bdc_container ."' where rowid = '". $bdc_row ."'  ";
		$stmt = sqlsrv_query($connect, $sql);
        /* Execute */		
        // $pesan = oci_error($stmt);
		// $msg = $pesan['message'];
		// 	if($msg == ''){
		// 		$msg = 'Save Success !';
		// 	}else{
		// 		$msg = " Save Failed ";
		// 	}		
	};

	//print_r($msg, true);
	echo $msg;
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>