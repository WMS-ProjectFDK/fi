<?php
// error_reporting(0);
session_start();
$msg = '';
if (isset($_SESSION['id_wms'])){
	include("../../connect/conn.php");

	$qry = "update ztb_wh_kanban_trans_fg set flag = 1 where flag = 0 ";
	$result = sqlsrv_query($connect, $qry);

	if($result === false ) {
        if(($errors = sqlsrv_errors() ) != null) {  
            foreach( $errors as $error){  
                $msg .= "message: ".$error[ 'message']."<br/>";  
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