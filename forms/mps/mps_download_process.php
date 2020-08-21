<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../../connect/conn.php");


$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';

$response = array();        $response2 = array();
$rowno=0;

if (isset($_SESSION['id_wms'])){

    //RUN procedure
	$sql = "{call zsp_compare_mps(?,?)}";
	$params = array(  
        array(  trim($date_awal)  , SQLSRV_PARAM_IN),
        array(  trim($date_akhir)  , SQLSRV_PARAM_IN),
	);
	$stmt = sqlsrv_query($connect, $sql,$params);



    $sql = "select SYS_DATE,
                   FLG,
                   UPLOAD_DATE,
                   ITEM_NO,
                   ITEM_NAME,
                   BATERY_TYPE,
                   CELL_GRADE,
                   PO_NO,PO_LINE_NO,
                   WORK_ORDER,
                   CONSIGNEE,PACKAGE_TYPE,
                   DATE_CODE,CR_DATE,REQUESTED_ETD,STATUS,LABEL_ITEM_NUMBER,
                   LABEL_NAME,QTY,MAN_POWER,OPERATION_TIME,LABEL_TYPE,CAPACITY,REMARK
                   from zvw_mps
    ";

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

        $sqlD = "select PO_NO, PO_LINE_NO, convert(nvarchar(10),MPS_DATE, 105) as MPS_DATE_FORMAT, MPS_QTY
            from MPS_DETAILS 
            where PO_NO = '".$dt->PO_NO."'
            and PO_LINE_NO = '".$dt->PO_LINE_NO."'
            --and MPS_DATE >= getdate()-7
            and MPS_DATE >= (select DATEADD(day,1,EOMONTH(getdate(),-2)))
            order by MPS_DATE";
        $dataD = sqlsrv_query($connect, strtoupper($sqlD));
        while($dtD = sqlsrv_fetch_object($dataD)){
            array_push($response2, $dtD);
        }
    }

    $fp = fopen('mps_download_result.json', 'w');
	fwrite($fp, json_encode($response));
    fclose($fp);
    
    $fp2 = fopen('mps_download_result_details.json', 'w');
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