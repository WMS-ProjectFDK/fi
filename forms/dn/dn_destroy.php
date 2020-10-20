<?php
$dn_no = strval($_REQUEST['dn_no']);
include("../../connect/conn.php");
$msg = '';

$del = "delete from dn_header where dn_no='".$dn_no."'";
$data_del = sqlsrv_query($connect, $del);

if( $data_del === false ) {
	if( ($errors = sqlsrv_errors() ) != null) {
         foreach($errors as $error){
            $msg .= $error[ 'message']; 
         }
    }
}

if($msg != ''){
	$msg .= " Delete-Header Process Error : $del";
	//break;
}

$del2 = "delete from dn_details where dn_no='".$dn_no."'";
$data_del2 = sqlsrv_query($connect, $del2);

if( $data_del2 === false ) {
	if( ($errors = sqlsrv_errors() ) != null) {
         foreach($errors as $error){
            $msg .= $error[ 'message']; 
         }
    }
}

if($msg != ''){
	$msg .= " Delete-Details Process Error : $del2";
	//break;
}

if ($msg != ''){
	echo json_encode(array('errorMsg'=>$msg));
}else{
	echo json_encode(array('success'=>true));	
}
?>