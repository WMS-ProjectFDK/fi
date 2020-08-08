<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../../connect/conn.php");

if (isset($_SESSION['id_wms'])){
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
    $msg = '';

    foreach($queries as $query){
		$do_no = $query->do_no;
		$bl_date = $query->bl_date;
        
        $sql = "{call SALES_RESTORE(?,?)}";		
        $params = array(  
            array(  $do_no  , SQLSRV_PARAM_IN),
            array(  $bl_date  , SQLSRV_PARAM_IN)
        );
        
        $stmt = sqlsrv_query($connect, $sql,$params);

        if($stmt === false ) {
            if(($errors = sqlsrv_errors() ) != null) {  
                foreach( $errors as $error){  
                    $msg .= "message: ".$error[ 'message']."<br/>";  
                }  
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