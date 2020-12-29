<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../../connect/conn.php");
$msg = '';

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
    $msg .= "Procedure Run Error  : $res";
    echo json_encode($msg);
}else{
    echo json_encode("success");
}
?>