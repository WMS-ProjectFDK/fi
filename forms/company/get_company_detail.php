<?php
session_start();
ini_set('max_execution_time', -1);
include("../../connect/conn.php");

$company_code = isset($_REQUEST['company_code']) ? strval($_REQUEST['company_code']) : '';

$sql  = " select 
                    COMPANY_CODE,
                    case COMPANY_TYPE
                        when 1 then 'Customer'
                        when 2 then 'Customer / Vendor'
                        when 3 then 'Vendor'
                        when 4 then 'Sub Contractor'
                        when 5 then 'Plant'
                        when 7 then 'Ship to'
                    end as COMPANY_TYPE,
                    COMPANY,
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
                    BC_DOC_REVERSE
                    
                    from COMPANY where delete_type is null
                         and company_code = '$company_code' " ;
                  
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
