<?php
session_start();
ini_set('max_execution_time', -1);
include("../../connect/conn.php");

$company_no = isset($_REQUEST['company_no']) ? strval($_REQUEST['company_no']) : '';
$ck_company_no = isset($_REQUEST['ck_company_no']) ? strval($_REQUEST['ck_company_no']) : '';
$company_type = isset($_REQUEST['company_type']) ? strval($_REQUEST['company_type']) : '';
$ck_company_type = isset($_REQUEST['ck_company_type']) ? strval($_REQUEST['ck_company_type']) : '';

$items = array();
$rowno=0;

if ($ck_company_no != "true"){
    $sqlCompanyCode = "company_code = '$company_no' and "; 
}else{
    $sqlCompanyCode = " ";
}

if ($ck_company_type != "true"){
    $sqlCompanyType = "company_type = $company_type and ";
}else{
    $sqlCompanyType = " ";
}

$where = "where $sqlCompanyCode $sqlCompanyType delete_type is null";

$sql  = "select COMPANY_CODE COMPANY_NO, COMPANY COMPANY_NAME,
                case COMPANY_TYPE
                    when 1 then 'Customer'
                    when 2 then 'Customer / Vendor'
                    when 3 then 'Vendor'
                    when 4 then 'Sub Contractor'
                    when 5 then 'Plant'
                    when 7 then 'Ship to'
                end as company_type,
                ADDRESS1, ADDRESS2, ADDRESS3, ADDRESS4,
                ATTN, TEL_NO, FAX_NO,
                cast(pdays as varchar(10)) +' '+ pdesc as TERMS    
                from COMPANY 
                $where" ;

$data_cek = sqlsrv_query($connect, strtoupper($sql));

while($row = sqlsrv_fetch_object($data_cek)){
	array_push($items, $row);
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>
