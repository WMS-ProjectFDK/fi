<?php
// error_reporting(0);
// session_start();
$msg = '';
// if (isset($_SESSION['id_wms'])){
	include("../../connect/conn.php");

	$qry = "update ztb_wh_kanban_trans_fg set flag = 1 
		where slip_no in (
			select z.slip_no
			from ZTB_WH_KANBAN_TRANS_FG z
			inner join production_income i on z.slip_no = i.slip_no
			where flag = 0 and
			(select convert(nvarchar(6), slip_date,112)) 
			between (select distinct last_month from whinventory) and (select distinct this_month from whinventory)
		)";
	$result = sqlsrv_query($connect, $qry);

	if($result === false ) {
        if(($errors = sqlsrv_errors() ) != null) {  
            foreach( $errors as $error){  
                $msg .= "message: ".$error[ 'message']."<br/>";  
            }  
        }
    }
// }else{
// 	$msg .= 'Session Expired';
// }

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode('success');
}
?>