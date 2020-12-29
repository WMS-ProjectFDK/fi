<?php
	session_start();
	include("../../connect/conn.php");
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	$supplier_code = isset($_REQUEST['supplier_code']) ? strval($_REQUEST['supplier_code']) : '';
    $curr_code = isset($_REQUEST['curr_code']) ? strval($_REQUEST['curr_code']) : '';
	$estimate_price = isset($_REQUEST['estimate_price']) ? strval($_REQUEST['estimate_price']) : '';

	
	$sql = "IF NOT EXISTS(select * from sp_ref where item_no = '$item_no' and customer_code = '$supplier_code') BEGIN 
				insert into SP_REF (CUSTOMER_CODE,ITEM_NO,U_PRICE,CURR_CODE,UPTO_DATE,REG_DATE,ORIGIN_CODE)
				select '$supplier_code','$item_no',$estimate_price, $curr_code,getdate(),GETDATE(),118 		
           END ELSE BEGIN RAISERROR('ITEM & SUPPLIER ALREADY REGISTERED',16,1);  END";

	
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
		echo json_encode("success");
	}
?>


