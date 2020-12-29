<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
ini_set('memory_limit', '-1');
set_time_limit(0);
include("../../connect/conn.php");

$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
$dt = json_decode(json_encode($data));
$str = preg_replace('/\\\\\"/',"\"", $dt);
$queries = json_decode($str);
$msg = '';

foreach($queries as $query){
	$SLIP_NO = $query->SLIP_NO;

	$qry = "update ztb_wh_kanban_trans_fg set flag = 1 where slip_no = '$SLIP_NO' ";
	$result = sqlsrv_query($connect, $qry);

	if($result === false ) {
        if(($errors = sqlsrv_errors() ) != null) {  
            foreach( $errors as $error){  
                $msg .= "message: ".$error[ 'message']."<br/>".$qry;  
            }  
        }
    }else{
        $ins2 = "insert into ZTB_LOG_HISTORY_DTL 
            VALUES ('FI0074', 
                    'Eko Budi', 
                    'FG (KURAIRE)', 
                    'APPROVE', 
                    '".$SLIP_NO."', 
                    SYSDATETIME()
                )";
        $insert2 = sqlsrv_query($connect, $ins2);
    }
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode('success');
}
?>