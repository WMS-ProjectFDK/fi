<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../../connect/conn.php");

if (isset($_SESSION['id_wms'])){
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
	$msg = '';

	foreach($queries as $query){
		$item_no = $query->item_no;

		//CEK GR_HEADERS
		$cek = "select count(*) as jum from ztb_safety_stock where item_no='$item_no' and year='MSTR'";
		$data = sqlsrv_query($connect, $cek);
		$dt = sqlsrv_fetch_object($data);

		if(intval($dt->jum) == 0){
			$ins  = "insert into ztb_safety_stock values($item_no,0,'MSTR',0,1,'N',1)";
			$data_ins = sqlsrv_query($connect, $ins);
			if($data_ins === false ) {
				if(($errors = sqlsrv_errors()) != null) {  
			        foreach( $errors as $error){  
			            $msg .= $error[ 'message']."<br/>";  
			        }
			    }
			}
			 
			if($msg != ''){
				$msg .= "Save item safety stock Error  : $ins";
				break;
			}
		}
	}

	$sql = "{call ZSP_SAFETY_STOCK_1}";
	$stmt = sqlsrv_query($connect, $sql);

	if($stmt === false ) {
		if(($errors = sqlsrv_errors()) != null) {  
	        foreach( $errors as $error){  
	            $msg .= $error[ 'message']."<br/>";  
	        }
	    }
	}

	if($msg != ''){
		$msg .= "Proses Create Safety_stock Error";
	}
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode("success");
}
// <!-- Fatal error: 'break' not in the 'loop' or 'switch' context in E:\xampp\htdocs\fi\forms\master\safety_stock_save.php on line 53 -->
?>