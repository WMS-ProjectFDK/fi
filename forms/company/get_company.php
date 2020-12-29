<?php
session_start();
ini_set('max_execution_time', -1);
include("../../connect/conn.php");

$company_no = isset($_REQUEST['company_no']) ? strval($_REQUEST['company_no']) : '';
$company_type = isset($_REQUEST['company_type']) ? strval($_REQUEST['company_type']) : '';
$company_name = isset($_REQUEST['company_name']) ? strval($_REQUEST['company_name']) : '';

$sqlCompanyCode = '';
$company_type = '';

if ($company_no != ''){
    $sqlCompanyCode = "and company_code = '$company_no' "; 
}

if ($company_type != 'None'){
    $sqlCompanyType = "and company_type like '%$company_type%' ";
}

$sql  = " select    COMPANY_CODE COMPANY_NO,
                    COMPANY COMPANY_NAME,
                    case COMPANY_TYPE
                        when 1 then 'Customer'
                        when 2 then 'Customer / Vendor'
                        when 3 then 'Vendor'
                        when 4 then 'Sub Contractor'
                        when 5 then 'Plant'
                        when 7 then 'Ship to'
                    end as company_type,
                    ADDRESS1,
                    ADDRESS2,
                    ADDRESS3,
                    ADDRESS4,
                    ATTN,
                    TEL_NO,
                    FAX_NO,
                    ZIP_CODE,
                    COUNTRY_CODE,
                    CURR_CODE,
                    TTERM,
                    PDAYS,
                    PDESC,
                    CASE_MARK,
                    EDI_CODE,
                    VAT,
                    SUPPLY_TYPE,
                    SUBC_CODE,
                    TRANSPORT_DAYS,
                    CC,
                    COMPANY_SHORT,
                    TAXPAYER_NO,
                    E_MAIL,
                    QUOT_SALE_CODE,
                    FORECAST_FLG,
                    ACCPAC_COMPANY_CODE,
                    BONDED_TYPE,
                    BC_DOC,
                    BC_DOC_REVERSE,
                    cast(pdays as varchar(10)) + pdesc as TERMS    
                    from COMPANY where delete_type is null 
                    $sqlCompanyCode $sqlCompanyType " ;

$data_cek = sqlsrv_query($connect, strtoupper($sql));

$items = array();
$rowno=0;

while($row = sqlsrv_fetch_object($data_cek)){
	array_push($items, $row);
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>
