<?php
// http://localhost:8088/fi/forms/packaging_plan/packaging_plan_download_proses.php?KEYWORD=FI0122&YM=202008&YMF=AUG-2020&IP=AUTO%20SHRINK%20LR03

header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../../connect/conn.php");

$YM = isset($_REQUEST['YM']) ? strval($_REQUEST['YM']) : '';
$YMF = isset($_REQUEST['YMF']) ? strval($_REQUEST['YMF']) : '';
$IP = isset($_REQUEST['IP']) ? strval($_REQUEST['IP']) : '';

$response = array();        $response2 = array();
$rowno=0;

if (isset($_SESSION['id_wms'])){
    $ip_res = '';
    if ($IP != ''){
        $ip_res = "and z.package_type in ('$IP')";
    }

    $sql = "select l.LABEL_TYPE_NAME as LABEL_TYPE, m_h.WORK_ORDER, m_h.ITEM_NO, i.DESCRIPTION as ITEM_NAME, m_h.DATE_CODE, 
        c.CLASS_1 + c.CLASS_2 as BATTERY_TYPE, i.GRADE_CODE as CELL_GRADE, m_h.QTY, CAST(m_h.CR_DATE as varchar(10)) as CR_DATE, 
        i.OPERATION_TIME, m_h.PO_NO, m_h.PO_LINE_NO, CAST(m_h.UPLOAD_DATE as varchar(10)) as UPLOAD_DATE
        from MPS_HEADER m_h,
        (select distinct PO_NO, PO_LINE_NO
         from MPS_DETAILS
         where LEFT(CONVERT(varchar, MPS_DATE,112),6) = '$YM'
        ) m_d, 
        ITEM i, CLASS c, ztb_item_pck z, LABEL_TYPE l
        where m_h.PO_NO = m_d.PO_NO
        and m_h.PO_LINE_NO = m_d.PO_LINE_NO
        and m_h.ITEM_NO = i.ITEM_NO
        and m_h.ITEM_NO = z.ITEM_NO
        and i.CLASS_CODE = c.CLASS_CODE
        and i.LABEL_TYPE = l.LABEL_TYPE_CODE
        ".$ip_res."
        order by l.LABEL_TYPE_NAME, c.CLASS_1 + c.CLASS_2, m_h.CR_DATE";

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

        $sqlD = "select  PO_NO, PO_LINE_NO, CONVERT(varchar,MPS_DATE,103) as mps_date, 
            MPS_QTY, CONVERT(varchar,UPLOAD_DATE,103) as UPLOAD_DATE
            from MPS_DETAILS m_d
            where LEFT(CONVERT(varchar, MPS_DATE,112),6) = '$YM'
            and PO_NO+'-'+PO_LINE_NO = '".$dt->PO_NO.'-'.$dt->PO_LINE_NO."'";
        $dataD = sqlsrv_query($connect, strtoupper($sqlD));

        while($dtD = sqlsrv_fetch_object($dataD)){
            array_push($response2, $dtD);
        }
    }

    $fp = fopen('packaging_plan_download_result.json', 'w');
	fwrite($fp, json_encode($response));
    fclose($fp);
    
    $fp2 = fopen('packaging_plan_download_result_details.json', 'w');
	fwrite($fp2, json_encode($response2));
	fclose($fp2);
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
    echo json_encode($msg);
}else{
	echo json_encode('success');
}
?>