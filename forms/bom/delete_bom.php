<?php
session_start();
ini_set('max_execution_time', -1);
include("../../connect/conn.php");
$msg = '';

$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
$level_no = isset($_REQUEST['level_no']) ? strval($_REQUEST['level_no']) : '';


// OPERATION_DATE,UPPER_ITEM_NO,LOWER_ITEM_NO,LEVEL_NO,REVISION,LINE_NO,REFERENCE_NUMBER,QUANTITY,QUANTITY_BASE,FAILURE_RATE,USER_SUPPLY_FLAG,SUBCON_SUPPLY_FLAG,REMARK
$sql  = " delete structure where upper_item_no = '$item_no' and level_no = '$level_no'" ;
$stmt = sqlsrv_query($connect, $sql);

if($stmt === false ) {
    if(($errors = sqlsrv_errors() ) != null) {  
        foreach( $errors as $error){  
            $msg .= "message: ".$error[ 'message']."<br/>";  
        }  
    }
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode(array('success'=>true));
}

?>
