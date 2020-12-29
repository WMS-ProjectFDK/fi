<?php
	session_start();
	include("../../connect/conn.php");
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	$supplier_code = isset($_REQUEST['supplier_code']) ? strval($_REQUEST['supplier_code']) : '';
	$estimate_price = isset($_REQUEST['estimate_price']) ? strval($_REQUEST['estimate_price']) : '';
	$leadtime = isset($_REQUEST['leadtime']) ? strval($_REQUEST['leadtime']) : '';
	$rank = isset($_REQUEST['rank']) ? strval($_REQUEST['rank']) : '';
	$msg = '';
	

	
	$sql = "IF NOT EXISTS(select * from itemmaker where item_no = '$item_no' and alter_procedure = $rank and supplier_code <> '$supplier_code') BEGIN 
	        update itemmaker  set   operation_date = getdate(),
									alter_procedure = $rank,
									purchase_leadtime = $leadtime,
									estimate_price = $estimate_price
			where item_no = '$item_no' and supplier_code = '$supplier_code'
			END ELSE BEGIN RAISERROR('ITEM RANKING ALREADY REGISTERED',16,1);  END";

	// $sql = "IF EXISTS(select * from itemmaker where item_no = '$item_no' and alter_procedure = $rank) BEGIN 
	// 			update itemmaker  set  alter_procedure = alter_procedure + 1
	// 			where item_no = '$item_no' and alter_procedure >= $rank
	// 		END ";

	if (isset($_SESSION['id_wms'])){
		$data_ins = sqlsrv_query($connect, $sql);
		if($data_ins === false ) {
			if(($errors = sqlsrv_errors()) != null) {  
				foreach( $errors as $error){  
				 $msg .= $error[ 'message']."<br/>";  
				}
			}
		}	
							
	}else{
		$msg .= 'Session Expired';
	}
		
	if($msg != ''){
		echo json_encode($msg);
	}else{
		echo 'OK';
	}
?>


