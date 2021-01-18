<?php
session_start();
ini_set('max_execution_time', -1);
include("../../../connect/conn.php");

$company_code = isset($_REQUEST['company_code']) ? strval($_REQUEST['company_code']) : '';
$msg = "";

$sql  = " delete from sp_company where company_code = '$company_code'" ;
//$sql  = " delete from sp_company where company_code = 9999" ;


$data_del = sqlsrv_query($connect, strtoupper($sql));
if($data_del === false ) {
    if(($errors = sqlsrv_errors() ) != null) {  
        foreach( $errors as $error){  
             $msg .= "message: ".$error[ 'message']."<br/>";  
        }  
    }
 }

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode('OK');
}

?>
