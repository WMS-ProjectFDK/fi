<?php
	session_start();
	include("../../connect/conn.php");
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	$supplier_code = isset($_REQUEST['supplier_code']) ? strval($_REQUEST['supplier_code']) : '';
    $curr_code = isset($_REQUEST['curr_code']) ? strval($_REQUEST['curr_code']) : '';
	$estimate_price = isset($_REQUEST['estimate_price']) ? strval($_REQUEST['estimate_price']) : '';
	$leadtime = isset($_REQUEST['leadtime']) ? strval($_REQUEST['leadtime']) : '';
	$msg="";
	
	$sql = "IF NOT EXISTS(select * from itemmaker where item_no = '$item_no' and supplier_code = '$supplier_code') BEGIN 
	        insert into itemmaker (create_date,
									operation_date,
									item_no,
									line_no,
									alter_procedure,
									supplier_code,
									manufact_fail_rate,
									delivery_allowable,
									purchase_leadtime,
									estimate_price,
									curr_code,
									material_cost,
									semifinish_cost,
									manufacturing_cost,
									adjustment_price,
									trial_sample_price 
								   ) select 
									  getdate(),
									  getdate(),
									  '$item_no',
									  (select isnull(max(line_no),0)  + 1 from itemmaker where item_no = '$item_no'),
									  (select isnull(max(alter_procedure),0)  + 1 from itemmaker where item_no = '$item_no'),
									  '$supplier_code',
									  0,
									  0,
									  $leadtime,
									  $estimate_price,
									  $curr_code,
									  0,
									  0,
									  0,
									  0,
									  0
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
		echo 'OK';
	}
?>


