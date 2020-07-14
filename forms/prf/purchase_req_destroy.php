<?php
$prf_no = strval($_REQUEST['prf_no']);
include("../../connect/conn.php");
$msg = '';

$del = "delete from prf_header where prf_no='".$prf_no."'";
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
	break;
}

$del2 = "delete from prf_details where prf_no='".$prf_no."'";
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
	break;
}

$del3 = "delete from ztb_prf_sts where prf_no='".$prf_no."'";
$data_del3 = sqlsrv_query($connect, $del3);


if( $data_del3 === false ) {
	if( ($errors = sqlsrv_errors() ) != null) {
         foreach($errors as $error){
            $msg .= $error[ 'message']; 
         }
    }
}

if($msg != ''){
	$msg .= " Delete-PRF Status Process Error : $del3";
	break;
}

if ($msg != ''){
	echo json_encode(array('errorMsg'=>$msg));
}else{
	echo json_encode(array('success'=>true));	
}
?>