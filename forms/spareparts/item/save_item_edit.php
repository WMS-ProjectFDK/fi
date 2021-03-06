<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../../../connect/conn.php");
$msg = '';
$field = '';

if (isset($_SESSION['id_wms'])){
    $ITEM_NO = (strlen(htmlspecialchars($_REQUEST['ITEM_NO'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['ITEM_NO'])."'";
    $SPECIFICATION = (strlen(htmlspecialchars($_REQUEST['SPECIFICATION'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['SPECIFICATION'])."'";
    $DESCRIPTION = (strlen(htmlspecialchars($_REQUEST['DESCRIPTION'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['DESCRIPTION'])."'";
    $MACHINE_CODE = (strlen(htmlspecialchars($_REQUEST['MACHINE_CODE'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['MACHINE_CODE'])."'";
    $ITEM_TYPE1 = (strlen(htmlspecialchars($_REQUEST['ITEM_TYPE1'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['ITEM_TYPE1'])."'";
    $UNIT_STOCK = (strlen(htmlspecialchars($_REQUEST['UNIT_STOCK'])) == 0) ? 'NULL' : htmlspecialchars($_REQUEST['UNIT_STOCK']);
    $REPORTGROUP_CODE = (strlen(htmlspecialchars($_REQUEST['REPORTGROUP_CODE'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['REPORTGROUP_CODE'])."'";
    $CLASS_CODE = (strlen(htmlspecialchars($_REQUEST['CLASS_CODE'])) == 0) ? 'NULL' : htmlspecialchars($_REQUEST['CLASS_CODE']);
    $CURR_CODE = (strlen(htmlspecialchars($_REQUEST['CURR_CODE'])) == 0) ? 'NULL' : htmlspecialchars($_REQUEST['CURR_CODE']);
    $STOCK_SUBJECT_CODE = (strlen(htmlspecialchars($_REQUEST['STOCK_SUBJECT_CODE'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['STOCK_SUBJECT_CODE'])."'";
    $SAFETY_STOCK = (strlen(htmlspecialchars($_REQUEST['SAFETY_STOCK'])) == 0) ? 'NULL' : htmlspecialchars($_REQUEST['SAFETY_STOCK']);
    $PURCHASE_LEADTIME = (strlen(htmlspecialchars($_REQUEST['PURCHASE_LEADTIME'])) == 0) ? 'NULL' : htmlspecialchars($_REQUEST['PURCHASE_LEADTIME']);

    $field  = "UPTO_DATE            = CURRENT_TIMESTAMP, ";
    $field .= "DESCRIPTION          = SUBSTRING($DESCRIPTION,0,30), ";
    $field .= "DESCRIPTION_ORG      = $DESCRIPTION, ";
    $field .= "SPECIFICATION        = $SPECIFICATION, ";
    $field .= "MACHINE_CODE         = $MACHINE_CODE, ";
    $field .= "ITEM_TYPE1           = $ITEM_TYPE1, ";
    $field .= "UNIT_STOCK           = $UNIT_STOCK, ";
    $field .= "UOM_Q                = $UNIT_STOCK, ";
    $field .= "CURR_CODE            = $CURR_CODE, ";
    $field .= "REPORTGROUP_CODE     = $REPORTGROUP_CODE, ";
    $field .= "CLASS_CODE           = $CLASS_CODE, ";
    $field .= "STOCK_SUBJECT_CODE   = $STOCK_SUBJECT_CODE, ";
    $field .= "SAFETY_STOCK         = $SAFETY_STOCK, ";
    $field .= "SECTION_CODE         = 100, ";
    $field .= "PURCHASE_LEADTIME    = $PURCHASE_LEADTIME";
    trim($field);

    $ins = "update sp_item set $field where item_no = $ITEM_NO ";
    $data = sqlsrv_query($connect, strtoupper($ins));
    // echo $ins."<br/>";
    if($data === false ) {
        if(($errors = sqlsrv_errors() ) != null) {  
                foreach( $errors as $error){  
                $msg .= "message: ".$error[ 'message']."<br/>";  
                }  
        }
    }
}else{
    $msg .= 'Session Expired';
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode ('success');
}
?>