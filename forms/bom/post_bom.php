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
    $flag = 0;
    //OPERATION_DATE,UPPER_ITEM_NO,LOWER_ITEM_NO,LEVEL_NO,REVISION,LINE_NO,REFERENCE_NUMBER,QUANTITY,QUANTITY_BASE,FAILURE_RATE,USER_SUPPLY_FLAG,SUBCON_SUPPLY_FLAG,REMARK
    foreach($queries as $query){
		$upper_item_no = $query->upper_item_no;
        $lower_item_no = $query->lower_item_no;
        $level_no =  $query->level_no;
        $line_no = $query->line_no;
        $quantity = $query->quantity;
        $quantity_base = $query->quantity_base;
        $failure_rate = $query->failure_rate;

        if ($flag == 0){
            $sql = "delete from structure where upper_item_no = '$upper_item_no' and level_no = '$level_no'";
            $stmt = sqlsrv_query($connect, $sql);
    
            if($stmt === false ) {
                if(($errors = sqlsrv_errors() ) != null) {  
                    foreach( $errors as $error){  
                        $msg .= "message: ".$error[ 'message']."<br/>";  
                    }  
                }
            }
            $flag+=1;
        }
       

                
        $sql2 = "insert into structure (upper_item_no,lower_item_no,level_no,line_no,quantity,quantity_base,failure_rate,OPERATION_DATE)
                 select '$upper_item_no','$lower_item_no','$level_no','$line_no',$quantity,$quantity_base,$failure_rate,getdate() ";
        $stmt2 = sqlsrv_query($connect, $sql2);

        if($stmt2 === false ) {
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