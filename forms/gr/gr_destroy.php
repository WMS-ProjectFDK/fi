<?php
session_start();
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
}else{
	$ins2 = "insert into ZTB_LOG_HISTORY_DTL 
		VALUES ('".$_SESSION['id_wms']."', 
				'".$_SESSION['name_wms']."', 
				'GOODS RECEIVE', 
				'DELETE', 
				'".$gr_no."', 
				SYSDATETIME()
			)";
	$insert2 = sqlsrv_query($connect, $ins2);
}

if($msg != ''){
	echo json_encode(array('errorMsg'=>'GR-DELETE Process Error : '.$sql ));
	
}else{
	echo json_encode(array('successMsg'=>'success'));	
}
?>