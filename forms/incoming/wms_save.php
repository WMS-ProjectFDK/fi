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
		$grn = $query->wh_grn;
		$line = $query->wh_line;
		$qty = $query->wh_qty;
		$item = $query->wh_item;
		$pallet = $query->wh_pallet;
		$qtypallet = $query->wh_qtypallet;

		$p = intval($pallet);	$q=intval($qty);
		$j=intval($qtypallet);
		$time =microtime(true);
	    $micro_time=sprintf("%d",($time - floor($time)) * 1000000);
	    $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
	    $dtime = $date->format("YmdHisu");

	    $ins = "INSERT INTO ztb_wh_in_det(id,gr_no,line_no,qty,item_no,pallet,tanggal) VALUES ((select max(id) + 1 from ztb_wh_in_det),'$grn','$line',$qtypallet,'$item',$pallet,'$dtime')";
        $result = sqlsrv_query($connect, strtoupper($ins));
        
        if($result === false ) {
            if( ($errors = sqlsrv_errors() ) != null) {  
                 foreach( $errors as $error){  
                    $msq .= $error[ 'message']."<br/>";  
                 }  
            }
        }

		if($msg != ''){
			$msg .= " Incoming Process Error : $ins";
			break;
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