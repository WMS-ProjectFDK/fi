<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../../connect/conn.php");
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

		$sql = "{call ZSP_SHIP_DETAIL_1(?,?,?,?,?,?,?,?,?)}";		
		$params = array(  
			array(  $bdc_item  , SQLSRV_PARAM_IN),
			array(  $bdc_qty  , SQLSRV_PARAM_IN),
			array(  $bdc_ppbe  , SQLSRV_PARAM_IN),
			array(  $bdc_wono  , SQLSRV_PARAM_IN),
			array(  $bdc_row  , SQLSRV_PARAM_IN),
			array(  $bdc_answer_no  , SQLSRV_PARAM_IN),
			array(  $bdc_tw  , SQLSRV_PARAM_IN),
			array(  $bdc_enr  , SQLSRV_PARAM_IN),
			array(  $bdc_container  , SQLSRV_PARAM_IN),
		);
		
		$stmt = sqlsrv_query($connect, $sql,$params);
		if($stmt === false ) {
			if(($errors = sqlsrv_errors() ) != null) {  
				foreach( $errors as $error){  
					$msg .= "message: ".$error[ 'message']."<br/>";  
				}  
			}
		}
	};

	print_r($msg, true);
	echo json_encode(array('successMsg'=>$msg));
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>