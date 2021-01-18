<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
ini_set('max_execution_time', -1);
include("../../connect/conn.php");
$user_name = $_SESSION['id_wms'];

if (isset($_SESSION['id_wms'])){
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
	$user = $_SESSION['id_wms'];
	$msg = '';

	foreach($queries as $query){
		$str = '';
		$po_no = $query->po_no;
		$po_line_no = $query->po_line_no;
		$cr_date = $query->cr_date;
		$date_code = $query->date_code;
		$qty = $query->qty;
		$bom = $query->bom;
		$item = $query->item;
		$status = $query->status;
		$edit = $query->edit;
		$new_po_no = $po_no;
		$newDate = date("d-M-Y", strtotime($cr_date));


		if($edit == 'checked'){
			$str = 'EDIT';
		}

		//Update BOM Level
        $cek = "update ZTB_BOM_LEVEL set 
            level_no = '$bom' , 
            level_type = case when level_no <> '$bom' then 'EDIT' else '$str' end  
            where po_no = '$po_no' 
            and po_line_no = '$po_line_no' " ;
        $data = sqlsrv_query($connect, $cek);
        
		$msg = $pesan['message'];

		if ($msg != '') {
			break;
		};

		//Update MRP
        $cek = "update mps_header set 
            bom_level = '$bom' , 
            bom_edit_stat = case when bom_level <> '$bom' then 'EDIT' else '$str' end  ,
            item_no = '$item',
            po_no = '$po_no',
            po_line_no = '$po_line_no',
            date_code = '$date_code',
            qty = '$qty',
            status = '$status',
            cr_date = '$newDate' 
            where po_no = '$po_no' 
            and po_line_no = '$po_line_no' " ;
        $data = sqlsrv_query($connect, $cek);
        
		$msg = $pesan['message'];
		if ($msg != '') {
			break;
		};
		//Insert Log MRP Header
        $cek = "update mps_header set 
            bom_level = $bom , 
            bom_edit_stat = case when bom_level <> $bom then Edit else  end  ,
            item_no = $item,
            po_no = $po_no,
            po_line_no = $po_line_no,
            date_code = $date_code,
            qty = $qty,
            status = $status,
            cr_date = $newDate 
            where po_no = $po_no 
            and po_line_no = $po_line_no " ;

		$cek = "insert into ztb_log_mps (query,query_date,user_login) values ('$cek',(select TO_CHAR(SYSDATE, 'DD-MM-YYYY HH24:MI:SS') from dual),'$user_name')" ;
		$data = sqlsrv_query($connect, $cek);
		$msg = $pesan['message'];
		
		if ($msg != '') {
			break;
        };
        
		//Update MRP PO dan PO LINE Detail
        $cek = "update mps_details set 
            po_no = '$po_no',
            po_line_no = '$po_line_no' 
            where po_no = '$po_no' 
            and po_line_no = '$po_line_no' " ;
		$data = sqlsrv_query($connect, $cek);
		$msg = $pesan['message'];

		if ($msg != '') {
			break;
        };
        
		//Insert Log MRP Detail
        $cek = "update mps_details set 
            po_no = $po_no,
            po_line_no = $po_line_no 
            where po_no = $po_no 
            and po_line_no = $po_line_no " ;

		$cek = "insert into ztb_log_mps (query,query_date,user_login) values ('$cek',(select TO_CHAR(SYSDATE, 'DD-MM-YYYY HH24:MI:SS') from dual),'$user_name')" ;
		$data = sqlsrv_query($connect, $cek);
		$msg = $pesan['message'];
		if ($msg != '') {
			break;
		};
	}	
}else{
	$msg .= 'Session Expired';
}


if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode("success");
}
?>