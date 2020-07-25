<?php
	header("Content-type: application/json");
	date_default_timezone_set('Asia/Jakarta');
	session_start();
	//error_reporting(0);
	include("../../../connect/conn.php");
	

    $msg = 'Success';
	$sql = "{call ZSP_MRP_PM}";
	$stmt = sqlsrv_query($connect, $sql);
	if( $stmt === false )
			{
				//die( printf("$sql") );
				//echo "Error in executing statement 3.\n";
				$msg = 'error';
				die( print_r( sqlsrv_errors(), true));
	
			}	
	if ($msg == ''){
		$arrData[0] = array("Msg"=>true);
	}else{
		$arrData[0] = array("Msg"=>$msg);
	}
	
	echo json_encode($msg);
?>