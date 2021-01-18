<?php
// header('Content-Type: text/plain; charset="UTF-8"');
header("Content-type: application/json");
error_reporting(0);
session_start();
include("../../connect/conn.php");

$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
$cmb_item_low = isset($_REQUEST['cmb_item_low']) ? strval($_REQUEST['cmb_item_low']) : '';
$ck_item_low = isset($_REQUEST['ck_item_low']) ? strval($_REQUEST['ck_item_low']) : '';

if ($ck_item_no != "true"){
    $itm = "st.UPPER_ITEM_NO = '$item_no' and ";
}else{
    $itm = "";
}

if ($ck_item_low != "true"){
    $item_no_low = "st.LOWER_ITEM_NO = '$cmb_item_low' and ";
}else{
    $item_no_low = "";
}

$where = " where $itm $item_no_low st.UPPER_ITEM_NO is not null";

$response = array();        $response2 = array();
$rowno=0;

if (isset($_SESSION['id_wms'])){
    $sql = "

select st.UPPER_ITEM_NO, it1.ITEM as UPPER_ITEM, it1.DESCRIPTION as UPPER_DESCRIPTION, 
        it1.DRAWING_NO, it1.DRAWING_REV, it1.APPLICABLE_MODEL, it1.CATALOG_NO, u1.UNIT,
        st.LOWER_ITEM_NO, it2.ITEM as LOWER_ITEM, it2.DESCRIPTION as LOWER_DESCRIPTION, 
        max(st.LEVEL_NO) LEVEL_NO, st.LINE_NO, st.REFERENCE_NUMBER, st.QUANTITY, u2.UNIT, st.QUANTITY_BASE, it2.STANDARD_PRICE,
        st.FAILURE_RATE, st.USER_SUPPLY_FLAG, st.SUBCON_SUPPLY_FLAG
        from STRUCTURE st
        inner join item it1 on st.UPPER_ITEM_NO = it1.ITEM_NO
        inner join item it2 on st.LOWER_ITEM_NO = it2.ITEM_NO
        left join unit u1 on it1.UOM_Q = u1.UNIT_CODE
        left join unit u2 on it2.UOM_Q = u2.UNIT_CODE
$where
group by st.UPPER_ITEM_NO, it1.ITEM , it1.DESCRIPTION , 
        it1.DRAWING_NO, it1.DRAWING_REV, it1.APPLICABLE_MODEL, it1.CATALOG_NO, u1.UNIT,
        st.LOWER_ITEM_NO, it2.ITEM , it2.DESCRIPTION , 
        st.LINE_NO, st.REFERENCE_NUMBER, st.QUANTITY, u2.UNIT, st.QUANTITY_BASE, it2.STANDARD_PRICE,
        st.FAILURE_RATE, st.USER_SUPPLY_FLAG, st.SUBCON_SUPPLY_FLAG
 order by st.UPPER_ITEM_NO, st.LEVEL_NO, cast(st.LINE_NO as int) asc


";

    //echo $sql;
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

    $fp = fopen('bom_download_result.json', 'w');
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