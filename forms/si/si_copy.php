<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../../connect/conn.php");
$user = $_SESSION['id_wms'];

if (isset($_SESSION['id_wms'])){
    $KET = (strlen(htmlspecialchars($_REQUEST['KET'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['KET'])."'";
    $SI_NO = (strlen(htmlspecialchars($_REQUEST['SI_NO'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['SI_NO'])."'";
    $CONTRACT_NO = (strlen(htmlspecialchars($_REQUEST['CONTRACT_NO'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['CONTRACT_NO'])."'";
    $PERSON_NAME = (strlen(htmlspecialchars($_REQUEST['PERSON_NAME'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['PERSON_NAME'])."'";
    $GOODS_NAME = (strlen(htmlspecialchars($_REQUEST['GOODS_NAME'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['GOODS_NAME'])."'";
    $SHIPPER_NAME = (strlen(htmlspecialchars($_REQUEST['SHIPPER_NAME'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['SHIPPER_NAME'])."'";
    $SHIPPER_ADDR1 = (strlen(htmlspecialchars($_REQUEST['SHIPPER_ADDR1'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['SHIPPER_ADDR1'])."'";
    $SHIPPER_ADDR2 = (strlen(htmlspecialchars($_REQUEST['SHIPPER_ADDR2'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['SHIPPER_ADDR2'])."'";
    $SHIPPER_ADDR3 = (strlen(htmlspecialchars($_REQUEST['SHIPPER_ADDR3'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['SHIPPER_ADDR3'])."'";
    $SHIPPER_TEL = (strlen(htmlspecialchars($_REQUEST['SHIPPER_TEL'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['SHIPPER_TEL'])."'";
    $SHIPPER_FAX = (strlen(htmlspecialchars($_REQUEST['SHIPPER_FAX'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['SHIPPER_FAX'])."'";
    $SHIPPER_ATTN = (strlen(htmlspecialchars($_REQUEST['SHIPPER_ATTN'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['SHIPPER_ATTN'])."'";
    $LOAD_PORT_CODE = (strlen(htmlspecialchars($_REQUEST['LOAD_PORT_CODE'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['LOAD_PORT_CODE'])."'";
    $LOAD_PORT = (strlen(htmlspecialchars($_REQUEST['LOAD_PORT'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['LOAD_PORT'])."'";
    $DISCH_PORT_CODE = (strlen(htmlspecialchars($_REQUEST['DISCH_PORT_CODE'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['DISCH_PORT_CODE'])."'";
    $DISCH_PORT = (strlen(htmlspecialchars($_REQUEST['DISCH_PORT'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['DISCH_PORT'])."'";
    $FINAL_DEST_CODE = (strlen(htmlspecialchars($_REQUEST['FINAL_DEST_CODE'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['FINAL_DEST_CODE'])."'";
    $FINAL_DEST = (strlen(htmlspecialchars($_REQUEST['FINAL_DEST'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['FINAL_DEST'])."'";
    $PLACE_DELI_CODE = (strlen(htmlspecialchars($_REQUEST['PLACE_DELI_CODE'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['PLACE_DELI_CODE'])."'";
    $PLACE_DELI = (strlen(htmlspecialchars($_REQUEST['PLACE_DELI'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['PLACE_DELI'])."'";
    $SHIPPING_TYPE = (strlen(htmlspecialchars($_REQUEST['SHIPPING_TYPE'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['SHIPPING_TYPE'])."'";
    $PAYMENT_TYPE = (strlen(htmlspecialchars($_REQUEST['PAYMENT_TYPE'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['PAYMENT_TYPE'])."'";
    $PAYMENT_REMARK = (strlen(htmlspecialchars($_REQUEST['PAYMENT_REMARK'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['PAYMENT_REMARK'])."'";
    $FORWARDER_NAME = (strlen(htmlspecialchars($_REQUEST['FORWARDER_NAME'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['FORWARDER_NAME'])."'";
    $FORWARDER_ADDR1 = (strlen(htmlspecialchars($_REQUEST['FORWARDER_ADDR1'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['FORWARDER_ADDR1'])."'";
    $FORWARDER_ADDR2 = (strlen(htmlspecialchars($_REQUEST['FORWARDER_ADDR2'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['FORWARDER_ADDR2'])."'";
    $FORWARDER_ADDR3 = (strlen(htmlspecialchars($_REQUEST['FORWARDER_ADDR3'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['FORWARDER_ADDR3'])."'";
    $FORWARDER_TEL = (strlen(htmlspecialchars($_REQUEST['FORWARDER_TEL'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['FORWARDER_TEL'])."'";
    $FORWARDER_FAX = (strlen(htmlspecialchars($_REQUEST['FORWARDER_FAX'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['FORWARDER_FAX'])."'";
    $FORWARDER_ATTN = (strlen(htmlspecialchars($_REQUEST['FORWARDER_ATTN'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['FORWARDER_ATTN'])."'";
    $SPECIAL_INST = (strlen(htmlspecialchars($_REQUEST['SPECIAL_INST'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['SPECIAL_INST'])."'";
    $CONSIGNEE_NAME = (strlen(htmlspecialchars($_REQUEST['CONSIGNEE_NAME'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['CONSIGNEE_NAME'])."'";
    $CONSIGNEE_ADDR1 = (strlen(htmlspecialchars($_REQUEST['CONSIGNEE_ADDR1'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['CONSIGNEE_ADDR1'])."'";
    $CONSIGNEE_ADDR2 = (strlen(htmlspecialchars($_REQUEST['CONSIGNEE_ADDR2'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['CONSIGNEE_ADDR2'])."'";
    $CONSIGNEE_ADDR3 = (strlen(htmlspecialchars($_REQUEST['CONSIGNEE_ADDR3'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['CONSIGNEE_ADDR3'])."'";
    $CONSIGNEE_TEL = (strlen(htmlspecialchars($_REQUEST['CONSIGNEE_TEL'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['CONSIGNEE_TEL'])."'";
    $CONSIGNEE_FAX = (strlen(htmlspecialchars($_REQUEST['CONSIGNEE_FAX'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['CONSIGNEE_FAX'])."'";
    $CONSIGNEE_ATTN = (strlen(htmlspecialchars($_REQUEST['CONSIGNEE_ATTN'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['CONSIGNEE_ATTN'])."'";
    $NOTIFY_NAME = (strlen(htmlspecialchars($_REQUEST['NOTIFY_NAME'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['NOTIFY_NAME'])."'";
    $NOTIFY_ADDR1 = (strlen(htmlspecialchars($_REQUEST['NOTIFY_ADDR1'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['NOTIFY_ADDR1'])."'";
    $NOTIFY_ADDR2 = (strlen(htmlspecialchars($_REQUEST['NOTIFY_ADDR2'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['NOTIFY_ADDR2'])."'";
    $NOTIFY_ADDR3 = (strlen(htmlspecialchars($_REQUEST['NOTIFY_ADDR3'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['NOTIFY_ADDR3'])."'";
    $NOTIFY_TEL = (strlen(htmlspecialchars($_REQUEST['NOTIFY_TEL'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['NOTIFY_TEL'])."'";
    $NOTIFY_FAX = (strlen(htmlspecialchars($_REQUEST['NOTIFY_FAX'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['NOTIFY_FAX'])."'";
    $NOTIFY_ATTN = (strlen(htmlspecialchars($_REQUEST['NOTIFY_ATTN'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['NOTIFY_ATTN'])."'";
    $NOTIFY_NAME_2 = (strlen(htmlspecialchars($_REQUEST['NOTIFY_NAME_2'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['NOTIFY_NAME_2'])."'";
    $NOTIFY_ADDR1_2 = (strlen(htmlspecialchars($_REQUEST['NOTIFY_ADDR1_2'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['NOTIFY_ADDR1_2'])."'";
    $NOTIFY_ADDR2_2 = (strlen(htmlspecialchars($_REQUEST['NOTIFY_ADDR2_2'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['NOTIFY_ADDR2_2'])."'";
    $NOTIFY_ADDR3_2 = (strlen(htmlspecialchars($_REQUEST['NOTIFY_ADDR3_2'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['NOTIFY_ADDR3_2'])."'";
    $NOTIFY_TEL_2 = (strlen(htmlspecialchars($_REQUEST['NOTIFY_TEL_2'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['NOTIFY_TEL_2'])."'";
    $NOTIFY_FAX_2 = (strlen(htmlspecialchars($_REQUEST['NOTIFY_FAX_2'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['NOTIFY_FAX_2'])."'";
    $NOTIFY_ATTN_2 = (strlen(htmlspecialchars($_REQUEST['NOTIFY_ATTN_2'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['NOTIFY_ATTN_2'])."'";
    $EMKL_NAME = (strlen(htmlspecialchars($_REQUEST['EMKL_NAME'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['EMKL_NAME'])."'";
    $EMKL_ADDR1 = (strlen(htmlspecialchars($_REQUEST['EMKL_ADDR1'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['EMKL_ADDR1'])."'";
    $EMKL_ADDR2 = (strlen(htmlspecialchars($_REQUEST['EMKL_ADDR2'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['EMKL_ADDR2'])."'";
    $EMKL_ADDR3 = (strlen(htmlspecialchars($_REQUEST['EMKL_ADDR3'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['EMKL_ADDR3'])."'";
    $EMKL_TEL = (strlen(htmlspecialchars($_REQUEST['EMKL_TEL'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['EMKL_TEL'])."'";
    $EMKL_FAX = (strlen(htmlspecialchars($_REQUEST['EMKL_FAX'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['EMKL_FAX'])."'";
    $EMKL_ATTN = (strlen(htmlspecialchars($_REQUEST['EMKL_ATTN'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['EMKL_ATTN'])."'";
    $SPECIAL_INFO = (strlen(htmlspecialchars($_REQUEST['SPECIAL_INFO'])) == 0) ? 'NULL' : htmlspecialchars($_REQUEST['SPECIAL_INFO']);
    $CUST_SI_NO = (strlen(htmlspecialchars($_REQUEST['CUST_SI_NO'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['CUST_SI_NO'])."'";
    $SINO_FROM_CUST = (strlen(htmlspecialchars($_REQUEST['SINO_FROM_CUST'])) == 0) ? 'NULL' : htmlspecialchars($_REQUEST['SINO_FROM_CUST']);

    // FOR SI_DOC
    $cmb_bl_doc = (strlen(htmlspecialchars($_REQUEST['cmb_bl_doc'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['cmb_bl_doc'])."'";
    $sheet_bl_doc = (strlen(htmlspecialchars($_REQUEST['sheet_bl_doc'])) == 0) ? 'NULL' : htmlspecialchars($_REQUEST['sheet_bl_doc']);
    $detail_bl_doc = (strlen(htmlspecialchars($_REQUEST['detail_bl_doc'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['detail_bl_doc'])."'";

    $cmb_certificate = (strlen(htmlspecialchars($_REQUEST['cmb_certificate'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['cmb_certificate'])."'";
    $sheet_certificate = (strlen(htmlspecialchars($_REQUEST['sheet_certificate'])) == 0) ? 'NULL' : htmlspecialchars($_REQUEST['sheet_certificate']);
    $detail_certificate = (strlen(htmlspecialchars($_REQUEST['detail_certificate'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['detail_certificate'])."'";

    $inv_doc = (strlen(htmlspecialchars($_REQUEST['inv_doc'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['inv_doc'])."'";
    $shett_inv = (strlen(htmlspecialchars($_REQUEST['shett_inv'])) == 0) ? 'NULL' : htmlspecialchars($_REQUEST['shett_inv']);
    $detail_inv = (strlen(htmlspecialchars($_REQUEST['detail_inv'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['detail_inv'])."'";

    $pack_doc = (strlen(htmlspecialchars($_REQUEST['pack_doc'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['pack_doc'])."'";
    $sheet_pack = (strlen(htmlspecialchars($_REQUEST['sheet_pack'])) == 0) ? 'NULL' : htmlspecialchars($_REQUEST['sheet_pack']);
    $detail_pack = (strlen(htmlspecialchars($_REQUEST['detail_pack'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['detail_pack'])."'";

    $rmk_s1 = explode('\n', $SPECIAL_INFO);
    $rmk_f1 = '';
    for($f1=0;$f1<count($rmk_s1);$f1++){
        if($rmk_s1[$f1] != ''  AND ! is_null($rmk_s1[$f1])) {
            if($f1 == count($rmk_s1)-1){
                $rmk_f1 .= $rmk_s1[$f1];
            }else{
                $rmk_f1 .= $rmk_s1[$f1].'char(13)' ;
            }
        }
    }

    $rmk_fix1 = str_replace("&amp;", "&", $rmk_f1);
    $SPECIAL_INFO_FIX = "'".$rmk_fix1."'";

    $field  = "OPERATION_DATE,"             ;       $value  = "CURRENT_TIMESTAMP,";
    $field .= "CREATE_DATE,"                ;       $value .= "GETDATE(),";
    $field .= "ENTRY_PERSON_CODE,"          ;       $value .= "'$user',";
    $field .= "IP_ADDRESS,"                 ;       $value .= "'0',";
    $field .= "SI_NO,"                      ;       $value .= "$SI_NO,";
    $field .= "CONTRACT_NO,"                ;       $value .= "$CONTRACT_NO,";
    $field .= "PERSON_NAME,"                ;       $value .= "$PERSON_NAME,";
    $field .= "GOODS_NAME,"                 ;       $value .= "$GOODS_NAME,";
    $field .= "SHIPPER_NAME,"               ;       $value .= "$SHIPPER_NAME,";
    $field .= "SHIPPER_ADDR1,"              ;       $value .= "$SHIPPER_ADDR1,";
    $field .= "SHIPPER_ADDR2,"              ;       $value .= "$SHIPPER_ADDR2,";
    $field .= "SHIPPER_ADDR3,"              ;       $value .= "$SHIPPER_ADDR3,";
    $field .= "SHIPPER_TEL,"                ;       $value .= "$SHIPPER_TEL,";
    $field .= "SHIPPER_FAX,"                ;       $value .= "$SHIPPER_FAX,";
    $field .= "SHIPPER_ATTN,"               ;       $value .= "$SHIPPER_ATTN,";
    $field .= "CUST_SI_NO,"                 ;       $value .= "$CUST_SI_NO,";
    $field .= "LOAD_PORT_CODE,"             ;       $value .= "$LOAD_PORT_CODE,";
    $field .= "LOAD_PORT,"                  ;       $value .= "$LOAD_PORT,";
    $field .= "DISCH_PORT_CODE,"            ;       $value .= "$DISCH_PORT_CODE,";
    $field .= "DISCH_PORT,"                 ;       $value .= "$DISCH_PORT,";
    $field .= "FINAL_DEST_CODE,"            ;       $value .= "$FINAL_DEST_CODE,";
    $field .= "FINAL_DEST,"                 ;       $value .= "$FINAL_DEST,";
    $field .= "PLACE_DELI_CODE,"            ;       $value .= "$PLACE_DELI_CODE,";
    $field .= "PLACE_DELI,"                 ;       $value .= "$PLACE_DELI,";
    $field .= "SHIPPING_TYPE,"              ;       $value .= "$SHIPPING_TYPE,";
    $field .= "PAYMENT_TYPE,"               ;       $value .= "$PAYMENT_TYPE,";
    $field .= "PAYMENT_REMARK,"             ;       $value .= "$PAYMENT_REMARK,";
    // $field .= "BL_DATE,"                    ;       $value .= ",";
    $field .= "FORWARDER_NAME,"             ;       $value .= "$FORWARDER_NAME,";
    $field .= "FORWARDER_ADDR1,"            ;       $value .= "$FORWARDER_ADDR1,";
    $field .= "FORWARDER_ADDR2,"            ;       $value .= "$FORWARDER_ADDR2,";
    $field .= "FORWARDER_ADDR3,"            ;       $value .= "$FORWARDER_ADDR3,";
    $field .= "FORWARDER_TEL,"              ;       $value .= "$FORWARDER_TEL,";
    $field .= "FORWARDER_FAX,"              ;       $value .= "$FORWARDER_FAX,";
    $field .= "FORWARDER_ATTN,"             ;       $value .= "$FORWARDER_ATTN,";
    $field .= "SPECIAL_INST,"               ;       $value .= "$SPECIAL_INST,";
    $field .= "CONSIGNEE_NAME,"             ;       $value .= "$CONSIGNEE_NAME,";
    $field .= "CONSIGNEE_ADDR1,"            ;       $value .= "$CONSIGNEE_ADDR1,";
    $field .= "CONSIGNEE_ADDR2,"            ;       $value .= "$CONSIGNEE_ADDR2,";
    $field .= "CONSIGNEE_ADDR3,"            ;       $value .= "$CONSIGNEE_ADDR3,";
    $field .= "CONSIGNEE_TEL,"              ;       $value .= "$CONSIGNEE_TEL,";
    $field .= "CONSIGNEE_FAX,"              ;       $value .= "$CONSIGNEE_FAX,";
    $field .= "CONSIGNEE_ATTN,"             ;       $value .= "$CONSIGNEE_ATTN,";
    $field .= "NOTIFY_NAME,"                ;       $value .= "$NOTIFY_NAME,";
    $field .= "NOTIFY_ADDR1,"               ;       $value .= "$NOTIFY_ADDR1,";
    $field .= "NOTIFY_ADDR2,"               ;       $value .= "$NOTIFY_ADDR2,";
    $field .= "NOTIFY_ADDR3,"               ;       $value .= "$NOTIFY_ADDR3,";
    $field .= "NOTIFY_TEL,"                 ;       $value .= "$NOTIFY_TEL,";
    $field .= "NOTIFY_FAX,"                 ;       $value .= "$NOTIFY_FAX,";
    $field .= "NOTIFY_ATTN,"                ;       $value .= "$NOTIFY_ATTN,";
    $field .= "NOTIFY_NAME_2,"              ;       $value .= "$NOTIFY_NAME_2,";
    $field .= "NOTIFY_ADDR1_2,"             ;       $value .= "$NOTIFY_ADDR1_2,";
    $field .= "NOTIFY_ADDR2_2,"             ;       $value .= "$NOTIFY_ADDR2_2,";
    $field .= "NOTIFY_ADDR3_2,"             ;       $value .= "$NOTIFY_ADDR3_2,";
    $field .= "NOTIFY_TEL_2,"               ;       $value .= "$NOTIFY_TEL_2,";
    $field .= "NOTIFY_FAX_2,"               ;       $value .= "$NOTIFY_FAX_2,";
    $field .= "NOTIFY_ATTN_2,"              ;       $value .= "$NOTIFY_ATTN_2,";
    $field .= "EMKL_NAME,"                  ;       $value .= "$EMKL_NAME,";
    $field .= "EMKL_ADDR1,"                 ;       $value .= "$EMKL_ADDR1,";
    $field .= "EMKL_ADDR2,"                 ;       $value .= "$EMKL_ADDR2,";
    $field .= "EMKL_ADDR3,"                 ;       $value .= "$EMKL_ADDR3,";
    $field .= "EMKL_TEL,"                   ;       $value .= "$EMKL_TEL,";
    $field .= "EMKL_FAX,"                   ;       $value .= "$EMKL_FAX,";
    $field .= "EMKL_ATTN,"                  ;       $value .= "$EMKL_ATTN,";
    $field .= "SPECIAL_INFO"                ;       $value .= "$SPECIAL_INFO_FIX";
    
    trim($field);
    trim($value);

    $ins = "INSERT INTO si_header ($field) VALUES ($value) ";
    // echo $ins;
    $data_ins = sqlsrv_query($connect, $ins);
    // $msg .= $ins;

    if($data_ins === false ) {
        if(($errors = sqlsrv_errors() ) != null) {  
            foreach( $errors as $error){  
                $msg .= "message: 1-".$error['message']."<br/>".$ins;  
            }  
        }
    }

    $exp_po = explode(', ', $SINO_FROM_CUST);
    $rowno = 1;
    // echo '<br/>';
    // echo count($exp_po);//[0];
    // echo '<br/>';
    // echo $exp_po[0];

    for ($i=0; $i < count($exp_po); $i++) { 
        if($exp_po[$i] != ''){
            $sql2 = "INSERT INTO si_po  (create_date,operation_date, si_no, line_no,po_no)
            VALUES (GETDATE(), CURRENT_TIMESTAMP, $SI_NO, $rowno , '$exp_po[$i]') ";
            // echo $sql2.'<br/>';
            $data_sql2 = sqlsrv_query($connect, strtoupper($sql2));
            if($data_sql2 === false ) {
                if(($errors = sqlsrv_errors() ) != null) {  
                    foreach( $errors as $error){  
                        $msg .= "message: 2-".$error[ 'message']."<br/>";  
                    } 
                }
            }
        }
        $rowno++;
    }

    // insert si doc-1
    $ins_doc1 = "insert into si_doc values (GETDATE(), CURRENT_TIMESTAMP, $SI_NO, 1, 'BL', $sheet_bl_doc, $cmb_bl_doc, $detail_bl_doc)";
    $data_doc1 = sqlsrv_query($connect, strtoupper($ins_doc1));
    // echo $ins_doc."<br/>";
    if($data_doc1 === false ) {
        if(($errors = sqlsrv_errors() ) != null) {  
            foreach( $errors as $error){  
                $msg .= "message: 3-1".$error[ 'message']."<br/>";  
            }  
        }
    }

    // insert si doc-2
    $ins_doc2 = "insert into si_doc values (GETDATE(), CURRENT_TIMESTAMP, $SI_NO, 2, 'CO', $sheet_certificate, $cmb_certificate, $detail_certificate)";
    $data_doc2 = sqlsrv_query($connect, strtoupper($ins_doc2));
    // echo $ins_doc."<br/>";
    if($data_doc2 === false ) {
        if(($errors = sqlsrv_errors() ) != null) {  
            foreach( $errors as $error){  
                $msg .= "message: 3-2".$error[ 'message']."<br/>";  
            }  
        }
    }

    // insert si doc-3
    $ins_doc3 = "insert into si_doc values (GETDATE(), CURRENT_TIMESTAMP, $SI_NO, 3, 'IV', $shett_inv, 'INVOICE', $detail_inv)";
    $data_doc3 = sqlsrv_query($connect, strtoupper($ins_doc3));
    // echo $ins_doc."<br/>";
    if($data_doc3 === false ) {
        if(($errors = sqlsrv_errors() ) != null) {  
            foreach( $errors as $error){  
                $msg .= "message: 3-3".$error[ 'message']."<br/>";  
            }  
        }
    }

    // insert si doc-4
    $ins_doc4 = "insert into si_doc values (GETDATE(), CURRENT_TIMESTAMP, $SI_NO, 4, 'PL', $sheet_pack, 'PACKING LIST', $detail_pack)";
    $data_doc4 = sqlsrv_query($connect, strtoupper($ins_doc4));
    // echo $ins_doc."<br/>";
    if($data_doc4 === false ) {
        if(($errors = sqlsrv_errors() ) != null) {  
            foreach( $errors as $error){  
                $msg .= "message: 3-4".$error[ 'message']."<br/>";  
            }  
        }
    }
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode('success');
}
?>