<?php
header('Content-Type: text/plain; charset="UTF-8"');
// error_reporting(0);
ini_set('memory_limit', '-1');
set_time_limit(0);
session_start();
include("../../connect/conn.php");

if (isset($_SESSION['id_wms'])){
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$bookingHeader = isset($_REQUEST['bookingHeader']) ? strval($_REQUEST['bookingHeader']) : '';
	$containerHeader = isset($_REQUEST['containerHeader']) ? strval($_REQUEST['containerHeader']) : '';

	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
	$msg = '';

	$sqlH = "delete from ztb_amz_container 
		where booking='$bookingHeader' and container='$containerHeader'";
	// echo $sqlH;
	$data_H = sqlsrv_query($connect, $sqlH);
	if($data_H === false ) {
		if(($errors = sqlsrv_errors() ) != null) {  
	         foreach( $errors as $error){  
	            $msg .= "message: ".$error[ 'message']."<br/>";  
	         }  
	    }
	}

	foreach($queries as $query){
		$plt_Booking = $query->plt_Booking;
		$plt_Container = $query->plt_Container;
		$plt_WO = $query->plt_WO;
		$plt_Item = $query->plt_Item;
		$plt_PO = $query->plt_PO;
		$plt_ASIN = $query->plt_ASIN;
		$plt_PALLET = $query->plt_PALLET;
		$plt_ID = $query->plt_ID;

		$plt_field  = "BOOKING,"		;	$plt_value   =  "'$plt_Booking',"	;
		$plt_field .= "CONTAINER,"		;	$plt_value  .=  "'$plt_Container',"	;
		$plt_field .= "WO,"				;	$plt_value  .=  "'$plt_WO',"		;
		$plt_field .= "item,"			;	$plt_value  .=  "'$plt_Item',"		;
		$plt_field .= "PO,"				;	$plt_value  .=  "'$plt_PO',"		;
		$plt_field .= "ASIN,"			;	$plt_value  .=  "'$plt_ASIN',"		;
		$plt_field .= "PALLET,"			;	$plt_value  .=  "$plt_PALLET,"		;
		$plt_field .= "ID"				;	$plt_value  .=  "'$plt_ID'"			;
		chop($plt_field);              		chop($plt_value);

		$ins_plt = "insert into ztb_amz_container ($plt_field) select $plt_value";
		$data_plt = sqlsrv_query($connect, $ins_plt);

		if($data_plt === false ) {
			if(($errors = sqlsrv_errors() ) != null) {  
		         foreach( $errors as $error){  
		            $msg .= "message: ".$error[ 'message']."<br/>";  
		         }  
		    }
		}

		if($msg != ''){
			$msg .= " Process insert pallet Error : $pesan";
			break;
		}
	}
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode('success');
}
?>