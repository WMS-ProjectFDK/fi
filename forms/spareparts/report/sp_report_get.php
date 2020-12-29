<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../../../connect/conn.php");

if (isset($_SESSION['id_wms'])){
	$period = isset($_REQUEST['period']) ? strval($_REQUEST['period']) : '';
	$user = $_SESSION['id_wms'];
    $msg = '';
    
    $sql = "{call zsp_spareparts_close_month_process(?)}";	
    $params = array(  
        array($period, SQLSRV_PARAM_IN)
    );
    $stmt = sqlsrv_query($connect, $sql, $params);
    
    if($stmt === false ) {
        if(($errors = sqlsrv_errors() ) != null) {  
            foreach( $errors as $error){  
                $msg .= "message: ".$error[ 'message'];  
            }  
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