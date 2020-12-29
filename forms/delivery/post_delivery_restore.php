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
        $answer_no = $query->answer_no;
        $do_no = $query->do_no;
        
        $sql = "{call DELIVERY_RESTORE(?)}";		
        $params = array(  
            array(  $answer_no  , SQLSRV_PARAM_IN)
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
                        'DELIVERY RESTORE', 
                        'RESTORE', 
                        '".$do_no.' ('.$answer_no.")', 
                        SYSDATETIME()
                    )";
            $insert2 = sqlsrv_query($connect, $ins2);
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