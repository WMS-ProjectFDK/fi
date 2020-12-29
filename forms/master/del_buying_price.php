<?php
	session_start();
	include("../../connect/conn.php");
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	$supplier_code = isset($_REQUEST['supplier_code']) ? strval($_REQUEST['supplier_code']) : '';
    $rank = isset($_REQUEST['rank']) ? strval($_REQUEST['rank']) : '';
	$msg = '';
	
	$sql = "update itemmaker set alter_procedure = alter_procedure - 1 where item_no = '$item_no'  and alter_procedure > $rank";
    $sql2 = "delete from itemmaker where item_no = '$item_no' and supplier_code = '$supplier_code'";

	
	if (isset($_SESSION['id_wms'])){
		$data_ins = sqlsrv_query($connect, $sql);
		if($data_ins === false ) {
			if(($errors = sqlsrv_errors()) != null) {  
				foreach( $errors as $error){  
				 $msg .= $error[ 'message']."<br/>";  
				}
			}
		}	

		$data_ins2 = sqlsrv_query($connect, $sql2);
		if($data_ins2 === false ) {
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


