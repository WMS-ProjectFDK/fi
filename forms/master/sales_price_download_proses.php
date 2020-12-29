<?php
// header('Content-Type: text/plain; charset="UTF-8"');
header("Content-type: application/json");
error_reporting(0);
session_start();
include("../../connect/conn.php");

$cmb_supplier = isset($_REQUEST['cmb_supplier']) ? strval($_REQUEST['cmb_supplier']) : '';
$ck_supplier = isset($_REQUEST['ck_supplier']) ? strval($_REQUEST['ck_supplier']) : '';
$cmb_item = isset($_REQUEST['cmb_item']) ? strval($_REQUEST['cmb_item']) : '';
$ck_item = isset($_REQUEST['ck_item']) ? strval($_REQUEST['ck_item']) : '';

if ($ck_supplier != "true"){
    $supp = "sp.CUSTOMER_CODE = '$cmb_supplier' and ";
}else{
    $supp = "";
}

if ($ck_item != "true"){
    $item = "sp.ITEM_NO = '$cmb_item' and ";
}else{
    $item = "";
}

$where = "where $supp $item sp.CUSTOMER_CODE is not null";

$response = array();        $response2 = array();
$rowno=0;

if (isset($_SESSION['id_wms'])){
    $sql = "select sp.CUSTOMER_CODE, com.COMPANY, sp.CUSTOMER_PART_NO, sp.ITEM_NO, itm.ITEM, itm.DESCRIPTION,
        curr.CURR_MARK, sp.U_PRICE
        from SP_REF sp
        left join company com on sp.CUSTOMER_CODE=com.COMPANY_CODE
        left join item itm on sp.item_no=itm.item_no
        left join CURRENCY curr on sp.CURR_CODE=curr.CURR_CODE
        $where
        order by com.COMPANY, sp.ITEM_NO asc";

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

    $fp = fopen('sales_price_download_result.json', 'w');
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