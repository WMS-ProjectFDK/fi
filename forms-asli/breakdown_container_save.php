<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn.php");
	$bdc_item = htmlspecialchars($_REQUEST['bdc_item']);
	$bdc_qty = htmlspecialchars($_REQUEST['bdc_qty']);
	$bdc_tw = htmlspecialchars($_REQUEST['bdc_tw']);
	$bdc_enr = htmlspecialchars($_REQUEST['bdc_enr']);
	$bdc_ppbe = htmlspecialchars($_REQUEST['bdc_ppbe']);
	$bdc_wono = htmlspecialchars($_REQUEST['bdc_wono']);
	$bdc_i = htmlspecialchars($_REQUEST['bdc_i']);
	$bdc_container = htmlspecialchars($_REQUEST['bdc_container']);
	$bdc_row = htmlspecialchars($_REQUEST['bdc_row']);
	$bdc_answer_no = htmlspecialchars($_REQUEST['bdc_answer_no']);
	
	if ($bdc_container != 'TOTAL'){
		$sql = "BEGIN ZSP_SHIP_DETAIL_1($bdc_item,'$bdc_qty','$bdc_ppbe','$bdc_wono','$bdc_container','$bdc_row','$bdc_answer_no','$bdc_tw','$bdc_enr'); end;";
		$stmt = oci_parse($connect, $sql);
		echo $sql;
		/* Execute */
		$res = oci_execute($stmt);
	};

	print_r($res, true);
	echo json_encode(array('successMsg'=>$bdc_i));
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>