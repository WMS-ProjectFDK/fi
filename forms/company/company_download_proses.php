<?php
// header('Content-Type: text/plain; charset="UTF-8"');
header("Content-type: application/json");
error_reporting(0);
session_start();
include("../../connect/conn.php");

$company_no = isset($_REQUEST['company_no']) ? strval($_REQUEST['company_no']) : '';
$company_type = isset($_REQUEST['company_type']) ? strval($_REQUEST['company_type']) : '';

if ($company_no != ""){
    $comp_no = "com.COMPANY_CODE = '$company_no' and ";
}else{
    $item_no = "";
}

if ($company_type != ""){
    $comp_type = "com.COMPANY_TYPE = '$company_type' and ";
}else{
    $comp_type = "";
}

$where = "where $comp_no $comp_type com.DELETE_TYPE is null";

$response = array();        $response2 = array();
$rowno=0;

if (isset($_SESSION['id_wms'])){
    $sql = "select com.COMPANY_CODE, com.COMPANY, com.COMPANY_TYPE, ct.TYPE_DESCRIPTION as TYPE, com.COUNTRY_CODE, cou.COUNTRY, 
        replace(com.ADDRESS1, '''', '') as ADDRESS1, 
        replace(com. ADDRESS2, '''', '') as ADDRESS2,
        replace(com.ADDRESS3, '''', '') as ADDRESS3,
        replace(com.ADDRESS4, '''', '') as ADDRESS4,
        com.ATTN, con.CONTRACT_SEQ, com.TTERM, cast(com.PDAYS as varchar)+' '+com.PDESC as PAYMENT, com.BONDED_TYPE
        from company com
        left join COMPANY_TYPE ct on com.COMPANY_TYPE = ct.COMPANY_TYPE
        left join COUNTRY cou on com.COUNTRY_CODE=cou.COUNTRY_CODE
        left join CONTRACT con on com.COMPANY_CODE=con.COMPANY_CODE
        $where 
        order by com.COMPANY_CODE asc";

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

    $fp = fopen('company_download_result.json', 'w');
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