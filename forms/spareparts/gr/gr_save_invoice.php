<?php
error_reporting(0);
session_start();
ini_set('max_execution_time', -1);
include("../../../connect/conn.php");


if (isset($_SESSION['id_wms'])){
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
    $msg = '';

    foreach($queries as $query){
		$gr_no = $query->gr_no;
		$inv_no = $query->inv_no;
        $inv_date = $query->inv_date;

        $sql  = " update sp_gr_header set inv_no = '$inv_no', inv_date = '$inv_date' where gr_no = '$gr_no'";
        $data_save = sqlsrv_query($connect, strtoupper($sql));

        if($data_save === false ) {
            if(($errors = sqlsrv_errors() ) != null) {  
                foreach( $errors as $error){  
                    $msg .= "message: ".$error[ 'message']."<br/>";  
                }  
            }
        }
    }
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo 'success';
}
?>
