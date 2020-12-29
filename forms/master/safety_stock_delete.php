<?php
include("../../connect/conn.php");
$item_no = strval($_REQUEST['item_no']);

$msg = '';

$del = "delete from ztb_safety_stock where item_no='".$item_no."' and period=0 and year='MSTR' ";
$data_del = sqlsrv_query($connect, $del);

if($data_del === false ) {
	if(($errors = sqlsrv_errors()) != null) {  
        foreach( $errors as $error){  
            $msg .= $error[ 'message']."<br/>";  
        }
    }
}

if($msg != ''){
	$msg .= "Delete Process Error : $del";
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
	// break;
}

if ($msg != ''){
	echo json_encode(array('errorMsg'=>$msg));
}else{
	echo json_encode(array('success'=>true));	
}
?>