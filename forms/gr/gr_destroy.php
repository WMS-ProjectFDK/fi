<?php
header("Content-type: application/json");
include("../../connect/conn.php");
$gr_no = strval($_REQUEST['gr_no']);
$msg = '';

$sql = "{call ZSP_GR_DELETE(?)}";		
	$params = array(  
		array($gr_no, SQLSRV_PARAM_IN)
	);
$stmt = sqlsrv_query($connect, $sql,$params);
if($stmt === false ) {
	if(($errors = sqlsrv_errors() ) != null) {  
        foreach( $errors as $error){  
           $msg .= "message: ".$error[ 'message']."<br/>";  
        }  
    }
}

if($msg != ''){
	echo json_encode(array('errorMsg'=>'GR-DELETE Process Error : '.$sql ));
	
}else{
	echo json_encode(array('successMsg'=>'success'));	
}
?>