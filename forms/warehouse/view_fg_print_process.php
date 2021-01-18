<?php
// header('Content-Type: text/plain; charset="UTF-8"');
header("Content-type: application/json");
error_reporting(0);
session_start();
include("../../connect/conn.php");

$date_awal_slip = isset($_REQUEST['date_awal_slip']) ? strval($_REQUEST['date_awal_slip']) : '';
$date_akhir_slip = isset($_REQUEST['date_akhir_slip']) ? strval($_REQUEST['date_akhir_slip']) : '';
$ck_slip_date = isset($_REQUEST['ck_slip_date']) ? strval($_REQUEST['ck_slip_date']) : '';
$date_awal_scan = isset($_REQUEST['date_awal_scan']) ? strval($_REQUEST['date_awal_scan']) : '';
$date_akhir_scan = isset($_REQUEST['date_akhir_scan']) ? strval($_REQUEST['date_akhir_scan']) : '';
$ck_scan_date = isset($_REQUEST['ck_scan_date']) ? strval($_REQUEST['ck_scan_date']) : '';
$slip_no = isset($_REQUEST['slip_no']) ? strval($_REQUEST['slip_no']) : '';
$ck_slip = isset($_REQUEST['ck_slip']) ? strval($_REQUEST['ck_slip']) : '';
$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
$ck_item = isset($_REQUEST['ck_item']) ? strval($_REQUEST['ck_item']) : '';
$wo_no = isset($_REQUEST['wo_no']) ? strval($_REQUEST['wo_no']) : '';
$ck_wo = isset($_REQUEST['ck_wo']) ? strval($_REQUEST['ck_wo']) : '';

if ($ck_slip_date != "true"){
    $slip_date = "a.slip_date between '$date_awal_slip' and '$date_akhir_slip' AND ";
}else{
    $slip_date = "";
}

if ($ck_scan_date != "true"){
    $scan_date = "b.date_in between '$date_awal_scan' and '$date_akhir_scan' AND ";
}else{
    $scan_date = "";
}

if ($ck_slip != "true"){
    $slip = "a.slip_no='$slip_no' and ";
}else{
    $slip = "";
}

if ($ck_item != "true"){
    $item = "a.item_no='$item_no' and ";
}else{
    $item = "";
}

if ($ck_wo != "true"){
    $wo = "a.wo_no='$wo_no' and ";
}else{
    $wo = "";
}

$where ="where $slip_date $scan_date $slip $item $wo a.slip_no is not null";

$response = array();        $response2 = array();
$rowno=0;

if (isset($_SESSION['id_wms'])){
    $ip_res = '';
    if ($IP != ''){
        $ip_res = "and z.package_type in ('$IP')";
    }

    $sql = "select slip_no, slip_date, item_no, item_name, item_description,
        approval_date, slip_quantity, wo_no, plt_no 
        from 
        (select distinct a.slip_no, cast(a.slip_date as varchar(10)) slip_date, a.item_no, a.item_name, a.item_description, 
                cast(a.approval_date as varchar(10)) as approval_date, a.slip_quantity, a.wo_no, c.plt_no--,
                --isnull(cast(b.date_in as varchar(10)),'BELUM DI SCAN') as scan
                from production_income a
                left join (select slip_no, date_in from ztb_wh_kanban_trans_fg) b on a.slip_no = b.slip_no
                left join (select cast(id as varchar(10)) as id, wo_no, plt_no from ztb_p_plan) c on a.slip_no = c.id
        		$where
        ) a
        order by a.item_no asc, a.slip_date asc";

    // echo $sql;
    $data = sqlsrv_query($connect, strtoupper($sql));

    if($data === false ) {
        if( ($errors = sqlsrv_errors() ) != null) {  
            foreach( $errors as $error){  
                $msg .= $error[ 'message']."<br/>";  
            }  
        }
    }

    while($dt = sqlsrv_fetch_object($data)){
        array_push($response, $dt);
    }

    $fp = fopen('view_fg_print_result.json', 'w');
	fwrite($fp, json_encode($response));
    fclose($fp);
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
    echo json_encode($msg);
}else{
    echo json_encode('success');
	// echo json_encode('SuccessMsg');
}
?>