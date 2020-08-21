<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../../connect/conn.php");

if (isset($_SESSION['id_wms'])){
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
    $user = $_SESSION['id_wms'];
    $ip = '0';//$_SERVER('HTTP_CLIENT_IP');//$_SERVER['SERVER_ADDR'];//$_SERVER['REMOTE_ADDR'];
	$msg = '';
    
	foreach($queries as $query){
        // [{"SI_NO":"202008159196","CONTRACT_NO":"NULL","PERSON_NAME":"","GOODS_NAME":"","SHIPPER_NAME":"FDK AMERICA, INC. C/O FDK CORPORATION","SHIPPER_ADDR1":null,"SHIPPER_ADDR2":null,"SHIPPER_ADDR3:null,"SHIPPER_TEL":null,"SHIPPER_FAX":null,"SHIPPER_ATTN":null,"LOAD_PORT_CODE":"AKL","LOAD_PORT":"AUCKLAND, NEW ZEALAND","DISCH_PORT_CODE":"AKL","DISCH_PORT":"AUCKLAND, NEW ZEALAND","FINAL_DEST_CODE":"AKL","FINAL_DEST":"AUCKLAND, NEW ZEALAND","PLACE_DELI_CODE":"AKL","PLACE_DELI":"AUCKLAND, NEW ZEALAND","SHIPPING_TYPE":"LCL","PAYMENT_TYPE":"Prepaid","PAYMENT_REMARK":"","FORWARDER_NAME":"BEN LINE AGENCY","FORWARDER_ADDR1":null,"FORWARDER_ADDR2":null,"FORWARDER_ADDR3":null,"FORWARDER_TEL":"Telp: 62-21-2522110 ext 1310","FORWARDER_FAX":"Fax:  62-21-2522112","FORWARDER_ATTN":"MS. YUNI","SPECIAL_INST":"AAA","SPECIAL_INFO":"AAA"}]

        $SI_STS = $query->SI_STS;
        $SI_NO_OLD = $query->SI_NO_OLD;
        $SI_NO = $query->SI_NO;
        $CONTRACT_NO = $query->CONTRACT_NO;
        $PERSON_NAME = $query->PERSON_NAME;
        $GOODS_NAME = $query->GOODS_NAME;
        $SHIPPER_NAME = $query->SHIPPER_NAME;
        $SHIPPER_ADDR1 = $query->SHIPPER_ADDR1;
        $SHIPPER_ADDR2 = $query->SHIPPER_ADDR2;
        $SHIPPER_ADDR3 = $query->SHIPPER_ADDR3;
        $SHIPPER_TEL = $query->SHIPPER_TEL;
        $SHIPPER_FAX = $query->SHIPPER_FAX;
        $SHIPPER_ATTN = $query->SHIPPER_ATTN;
        $CUST_SI_NO = $query->CUST_SI_NO;
        $LOAD_PORT_CODE = $query->LOAD_PORT_CODE;
        $LOAD_PORT = $query->LOAD_PORT;
        $DISCH_PORT_CODE = $query->DISCH_PORT_CODE;
        $DISCH_PORT = $query->DISCH_PORT;
        $FINAL_DEST_CODE = $query->FINAL_DEST_CODE;
        $FINAL_DEST = $query->FINAL_DEST;
        $PLACE_DELI_CODE = $query->PLACE_DELI_CODE;
        $PLACE_DELI = $query->PLACE_DELI;
        $SHIPPING_TYPE = $query->SHIPPING_TYPE;
        $PAYMENT_TYPE = $query->PAYMENT_TYPE;
        $PAYMENT_REMARK = $query->PAYMENT_REMARK;
        // BL_DATE:
        $FORWARDER_NAME = $query->FORWARDER_NAME;
        $FORWARDER_ADDR1 = $query->FORWARDER_ADDR1;
        $FORWARDER_ADDR2 = $query->FORWARDER_ADDR2;
        $FORWARDER_ADDR3 = $query->FORWARDER_ADDR3;
        $FORWARDER_TEL = $query->FORWARDER_TEL;
        $FORWARDER_FAX = $query->FORWARDER_FAX;
        $FORWARDER_ATTN = $query->FORWARDER_ATTN;
        $SPECIAL_INST = $query->SPECIAL_INST;
        $CONSIGNEE_NAME = $query->CONSIGNEE_NAME;
        $CONSIGNEE_ADDR1 = $query->CONSIGNEE_ADDR1;
        $CONSIGNEE_ADDR2 = $query->CONSIGNEE_ADDR2;
        $CONSIGNEE_ADDR3 = $query->CONSIGNEE_ADDR3;
        $CONSIGNEE_TEL = $query->CONSIGNEE_TEL;
        $CONSIGNEE_FAX = $query->CONSIGNEE_FAX;
        $CONSIGNEE_ATTN = $query->CONSIGNEE_ATTN;
        $NOTIFY_NAME = $query->NOTIFY_NAME;
        $NOTIFY_ADDR1 = $query->NOTIFY_ADDR1;
        $NOTIFY_ADDR2 = $query->NOTIFY_ADDR2;
        $NOTIFY_ADDR3 = $query->NOTIFY_ADDR3;
        $NOTIFY_TEL = $query->NOTIFY_TEL;
        $NOTIFY_FAX = $query->NOTIFY_FAX;
        $NOTIFY_ATTN = $query->NOTIFY_ATTN;
        $NOTIFY_NAME_2 = $query->NOTIFY_NAME_2;
        $NOTIFY_ADDR1_2 = $query->NOTIFY_ADDR1_2;
        $NOTIFY_ADDR2_2 = $query->NOTIFY_ADDR2_2;
        $NOTIFY_ADDR3_2 = $query->NOTIFY_ADDR3_2;
        $NOTIFY_TEL_2 = $query->NOTIFY_TEL_2;
        $NOTIFY_FAX_2 = $query->NOTIFY_FAX_2;
        $NOTIFY_ATTN_2 = $query->NOTIFY_ATTN_2;
        $EMKL_NAME = $query->EMKL_NAME;
        $EMKL_ADDR1 = $query->EMKL_ADDR1;
        $EMKL_ADDR2 = $query->EMKL_ADDR2;
        $EMKL_ADDR3 = $query->EMKL_ADDR3;
        $EMKL_TEL = $query->EMKL_TEL;
        $EMKL_FAX = $query->EMKL_FAX;
        $EMKL_ATTN = $query->EMKL_ATTN;
        $SPECIAL_INFO = $query->SPECIAL_INFO;

        if($SI_STS == 'add'){
            // insert ADD
            $field  = "OPERATION_DATE,"             ;       $value .= "GETDATE(),";
            $field .= "CREATE_DATE,"                ;       $value .= "GETDATE(),";
            $field .= "ENTRY_PERSON_CODE,"          ;       $value .= "'$user',";
            $field .= "IP_ADDRESS,"                 ;       $value .= "'$ip',";
            $field .= "SI_NO,"                      ;       $value .= "'$SI_NO',";
            $field .= "CONTRACT_NO,"                ;       $value .= "'$CONTRACT_NO',";
            $field .= "PERSON_NAME,"                ;       $value .= "'$PERSON_NAME',";
            $field .= "GOODS_NAME,"                 ;       $value .= "'$GOODS_NAME',";
            $field .= "SHIPPER_NAME,"               ;       $value .= "'$SHIPPER_NAME',";
            $field .= "SHIPPER_ADDR1,"              ;       $value .= "'$SHIPPER_ADDR1',";
            $field .= "SHIPPER_ADDR2,"              ;       $value .= "'$SHIPPER_ADDR2',";
            $field .= "SHIPPER_ADDR3,"              ;       $value .= "'$SHIPPER_ADDR3',";
            $field .= "SHIPPER_TEL,"                ;       $value .= "'$SHIPPER_TEL',";
            $field .= "SHIPPER_FAX,"                ;       $value .= "'$SHIPPER_FAX',";
            $field .= "SHIPPER_ATTN,"               ;       $value .= "'$SHIPPER_ATTN',";
            $field .= "CUST_SI_NO,"                 ;       $value .= "'$CUST_SI_NO',";
            $field .= "LOAD_PORT_CODE,"             ;       $value .= "'$LOAD_PORT_CODE',";
            $field .= "LOAD_PORT,"                  ;       $value .= "'$LOAD_PORT',";
            $field .= "DISCH_PORT_CODE,"            ;       $value .= "'$DISCH_PORT_CODE',";
            $field .= "DISCH_PORT,"                 ;       $value .= "'$DISCH_PORT',";
            $field .= "FINAL_DEST_CODE,"            ;       $value .= "'$FINAL_DEST_CODE',";
            $field .= "FINAL_DEST,"                 ;       $value .= "'$FINAL_DEST',";
            $field .= "PLACE_DELI_CODE,"            ;       $value .= "'$PLACE_DELI_CODE',";
            $field .= "PLACE_DELI,"                 ;       $value .= "'$PLACE_DELI',";
            $field .= "SHIPPING_TYPE,"              ;       $value .= "'$SHIPPING_TYPE',";
            $field .= "PAYMENT_TYPE,"               ;       $value .= "'$PAYMENT_TYPE',";
            $field .= "PAYMENT_REMARK,"             ;       $value .= "'$PAYMENT_REMARK',";
            // $field .= "BL_DATE,"                    ;       $value .= ",";
            $field .= "FORWARDER_NAME,"             ;       $value .= "'$FORWARDER_NAME',";
            $field .= "FORWARDER_ADDR1,"            ;       $value .= "'$FORWARDER_ADDR',";
            $field .= "FORWARDER_ADDR2,"            ;       $value .= "'$FORWARDER_ADDR',";
            $field .= "FORWARDER_ADDR3,"            ;       $value .= "'$FORWARDER_ADDR',";
            $field .= "FORWARDER_TEL,"              ;       $value .= "'$FORWARDER_TEL',";
            $field .= "FORWARDER_FAX,"              ;       $value .= "'$FORWARDER_FAX',";
            $field .= "FORWARDER_ATTN,"             ;       $value .= "'$FORWARDER_ATTN',";
            $field .= "SPECIAL_INST,"               ;       $value .= "'$SPECIAL_INST',";
            // $field .= "CONSIGNEE_NAME,"             ;       $value .= ",";
            // $field .= "CONSIGNEE_ADDR1,"            ;       $value .= ",";
            // $field .= "CONSIGNEE_ADDR2,"            ;       $value .= ",";
            // $field .= "CONSIGNEE_ADDR3,"            ;       $value .= ",";
            // $field .= "CONSIGNEE_TEL,"              ;       $value .= ",";
            // $field .= "CONSIGNEE_FAX"               ;       $value .= ",";
            // $field .= "CONSIGNEE_ATTN,"             ;       $value .= ",";
            $field .= "NOTIFY_NAME,"                ;       $value .= "'$NOTIFY_NAME',";
            $field .= "NOTIFY_ADDR1,"               ;       $value .= "'$NOTIFY_ADDR1',";
            $field .= "NOTIFY_ADDR2,"               ;       $value .= "'$NOTIFY_ADDR2',";
            $field .= "NOTIFY_ADDR3,"               ;       $value .= "'$NOTIFY_ADDR3',";
            $field .= "NOTIFY_TEL,"                 ;       $value .= "'$NOTIFY_TEL',";
            $field .= "NOTIFY_FAX,"                 ;       $value .= "'$NOTIFY_FAX',";
            $field .= "NOTIFY_ATTN,"                ;       $value .= "'$NOTIFY_ATTN',";
            $field .= "NOTIFY_NAME_2,"              ;       $value .= "'$NOTIFY_NAME_2',";
            $field .= "NOTIFY_ADDR1_2,"             ;       $value .= "'$NOTIFY_ADDR1_2',";
            $field .= "NOTIFY_ADDR2_2,"             ;       $value .= "'$NOTIFY_ADDR2_2',";
            $field .= "NOTIFY_ADDR3_2,"             ;       $value .= "'$NOTIFY_ADDR3_2',";
            $field .= "NOTIFY_TEL_2,"               ;       $value .= "'$NOTIFY_TEL_2',";
            $field .= "NOTIFY_FAX_2,"               ;       $value .= "'$NOTIFY_FAX_2',";
            $field .= "NOTIFY_ATTN_2,"              ;       $value .= "'$NOTIFY_ATTN_2',";
            $field .= "EMKL_NAME,"                  ;       $value .= "'$EMKL_NAME',";
            $field .= "EMKL_ADDR1,"                 ;       $value .= "'$EMKL_ADDR1',";
            $field .= "EMKL_ADDR2,"                 ;       $value .= "'$EMKL_ADDR2',";
            $field .= "EMKL_ADDR3,"                 ;       $value .= "'$EMKL_ADDR3',";
            $field .= "EMKL_TEL,"                   ;       $value .= "'$EMKL_TEL',";
            $field .= "EMKL_FAX,"                   ;       $value .= "'$EMKL_FAX',";
            $field .= "EMKL_ATTN,"                  ;       $value .= "'$EMKL_ATTN',";
            $field .= "SPECIAL_INFO"                ;       $value .= "'$SPECIAL_INFO'";
            chop($field);              			        chop($value);

            $ins = "INSERT INTO si_header ($field) SELECT $value ";
            //echo $ins;
            $data_ins = sqlsrv_query($connect, $ins);
            
            if($data_ins === false ) {
                if(($errors = sqlsrv_errors() ) != null) {  
                    foreach( $errors as $error){  
                        $msg .= "message: ".$error[ 'message']."<br/>";  
                    }  
                }
            }
        }else{
            // si_save.php?data=[{"SI_STS":"edit","SI_NO_OLD":"202006003651","SI_NO":"202008263377","CONTRACT_NO":"NULL","PERSON_NAME":"MS. MARIKO YAMAKAWA","GOODS_NAME":"FUJITSU BRAND ALKALINE MANGANESE BATTERIES","SHIPPER_NAME":"PT. FDK INDONESIA C/O FDK CORPORATION","LOAD_PORT_CODE":"TPP","LOAD_PORT":"TG. PRIOK - JAKARTA","DISCH_PORT_CODE":null,"DISCH_PORT":"SINGAPORE","FINAL_DEST_CODE":null,"FINAL_DEST":"SINGAPORE","PLACE_DELI_CODE":null,"PLACE_DELI":"SINGAPORE","SHIPPING_TYPE":"LCL","PAYMENT_TYPE":"Prepaid","PAYMENT_REMARK":"PAYABLE AT TOKYO","FORWARDER_NAME":"MITSUI-SOKO  INDONESIA / GATEWAY","SPECIAL_INST":"","SPECIAL_INFO":"W/H:\nPT. MITSUI-SOKO INDONESIA\nJL. ROROTAN NO. 8 KAWASAN INDUSTRI CAKUNG CILINCING\nJAKARTA UTARA 14140, INDONESIA\nPIC W/H: PAK SONY"}]

            $field2  = "OPERATION_DATE,"             ;       $value2 .= "GETDATE(),";
            $field2 .= "CREATE_DATE,"                ;       $value2 .= "GETDATE(),";
            $field2 .= "ENTRY_PERSON_CODE,"          ;       $value2 .= "'$user',";
            $field2 .= "IP_ADDRESS,"                 ;       $value2 .= "'$ip',";
            $field2 .= "SI_NO,"                      ;       $value2 .= "'$SI_NO',";
            $field2 .= "CONTRACT_NO,"                ;       $value2 .= "ISNULL(CONTRACT_NO,'$CONTRACT_NO'),";
            $field2 .= "PERSON_NAME,"                ;       $value2 .= "ISNULL(PERSON_NAME,'$PERSON_NAME'),";
            $field2 .= "GOODS_NAME,"                 ;       $value2 .= "ISNULL(GOODS_NAME,'$GOODS_NAME'),";
            $field2 .= "SHIPPER_NAME,"               ;       $value2 .= "ISNULL(SHIPPER_NAME,'$SHIPPER_NAME'),";
            $field2 .= "SHIPPER_ADDR1,"              ;       $value2 .= "ISNULL(SHIPPER_ADDR1,'$SHIPPER_ADDR1'),";
            $field2 .= "SHIPPER_ADDR2,"              ;       $value2 .= "ISNULL(SHIPPER_ADDR2,'$SHIPPER_ADDR2'),";
            $field2 .= "SHIPPER_ADDR3,"              ;       $value2 .= "ISNULL(SHIPPER_ADDR3,'$SHIPPER_ADDR3'),";
            $field2 .= "SHIPPER_TEL,"                ;       $value2 .= "ISNULL(SHIPPER_TEL,'$SHIPPER_TEL'),";
            $field2 .= "SHIPPER_FAX,"                ;       $value2 .= "ISNULL(SHIPPER_FAX,'$SHIPPER_FAX'),";
            $field2 .= "SHIPPER_ATTN,"               ;       $value2 .= "ISNULL(SHIPPER_ATTN,'$SHIPPER_ATTN'),";
            $field2 .= "CUST_SI_NO,"                 ;       $value2 .= "ISNULL(CUST_SI_NO, '$CUST_SI_NO'),";
            $field2 .= "LOAD_PORT_CODE,"             ;       $value2 .= "ISNULL(LOAD_PORT_CODE,'$LOAD_PORT_CODE'),";
            $field2 .= "LOAD_PORT,"                  ;       $value2 .= "ISNULL(LOAD_PORT,  '$LOAD_PORT'),";
            $field2 .= "DISCH_PORT_CODE,"            ;       $value2 .= "ISNULL(DISCH_PORT_CODE,'$DISCH_PORT_CODE'),";
            $field2 .= "DISCH_PORT,"                 ;       $value2 .= "ISNULL(DISCH_PORT, '$DISCH_PORT'),";
            $field2 .= "FINAL_DEST_CODE,"            ;       $value2 .= "ISNULL(FINAL_DEST_CODE,'$FINAL_DEST_CODE'),";
            $field2 .= "FINAL_DEST,"                 ;       $value2 .= "ISNULL(FINAL_DEST, '$FINAL_DEST'),";
            $field2 .= "PLACE_DELI_CODE,"            ;       $value2 .= "ISNULL(PLACE_DELI_CODE,'$PLACE_DELI_CODE'),";
            $field2 .= "PLACE_DELI,"                 ;       $value2 .= "ISNULL(PLACE_DELI, '$PLACE_DELI'),";
            $field2 .= "SHIPPING_TYPE,"              ;       $value2 .= "ISNULL(SHIPPING_TYPE,'$SHIPPING_TYPE'),";
            $field2 .= "PAYMENT_TYPE,"               ;       $value2 .= "ISNULL(PAYMENT_TYPE,'$PAYMENT_TYPE'),";
            $field2 .= "PAYMENT_REMARK,"             ;       $value2 .= "ISNULL(PAYMENT_REMARK,'$PAYMENT_REMARK'),";
            // $value2 .= "BL_DATE,"                         $value2 .= "BL_DATE," ;       $field2 .= ",";
            $field2 .= "FORWARDER_NAME,"             ;       $value2 .= "ISNULL(FORWARDER_NAME,'$FORWARDER_NAME'),";
            $field2 .= "FORWARDER_ADDR1,"            ;       $value2 .= "ISNULL(FORWARDER_ADDR1,'$FORWARDER_ADDR'),";
            $field2 .= "FORWARDER_ADDR2,"            ;       $value2 .= "ISNULL(FORWARDER_ADDR2,'$FORWARDER_ADDR'),";
            $field2 .= "FORWARDER_ADDR3,"            ;       $value2 .= "ISNULL(FORWARDER_ADDR3,'$FORWARDER_ADDR'),";
            $field2 .= "FORWARDER_TEL,"              ;       $value2 .= "ISNULL(FORWARDER_TEL,'$FORWARDER_TEL'),";
            $field2 .= "FORWARDER_FAX,"              ;       $value2 .= "ISNULL(FORWARDER_FAX,'$FORWARDER_FAX'),";
            $field2 .= "FORWARDER_ATTN,"             ;       $value2 .= "ISNULL(FORWARDER_ATTN,'$FORWARDER_ATTN'),";
            $field2 .= "SPECIAL_INST,"               ;       $value2 .= "ISNULL(SPECIAL_INST,'$SPECIAL_INST'),";
            $field2 .= "CONSIGNEE_NAME,"             ;       $value2 .= "ISNULL(CONSIGNEE_NAME,'$CONSIGNEE_NAME'),";
            $field2 .= "CONSIGNEE_ADDR1,"            ;       $value2 .= "ISNULL(CONSIGNEE_ADDR1,'$CONSIGNEE_ADDR1'),";
            $field2 .= "CONSIGNEE_ADDR2,"            ;       $value2 .= "ISNULL(CONSIGNEE_ADDR2,'$CONSIGNEE_ADDR2'),";
            $field2 .= "CONSIGNEE_ADDR3,"            ;       $value2 .= "ISNULL(CONSIGNEE_ADDR3,'$CONSIGNEE_ADDR3'),";
            $field2 .= "CONSIGNEE_TEL,"              ;       $value2 .= "ISNULL(CONSIGNEE_TEL,'$CONSIGNEE_TEL'),";
            $field2 .= "CONSIGNEE_FAX,"              ;       $value2 .= "ISNULL(CONSIGNEE_FAX,'$CONSIGNEE_FAX'),";
            $field2 .= "CONSIGNEE_ATTN,"             ;       $value2 .= "ISNULL(CONSIGNEE_ATTN,'$CONSIGNEE_ATTN'),";
            $field2 .= "NOTIFY_NAME,"                ;       $value2 .= "ISNULL(NOTIFY_NAME,'$NOTIFY_NAME'),";
            $field2 .= "NOTIFY_ADDR1,"               ;       $value2 .= "ISNULL(NOTIFY_ADDR1,'$NOTIFY_ADDR1'),";
            $field2 .= "NOTIFY_ADDR2,"               ;       $value2 .= "ISNULL(NOTIFY_ADDR2,'$NOTIFY_ADDR2'),";
            $field2 .= "NOTIFY_ADDR3,"               ;       $value2 .= "ISNULL(NOTIFY_ADDR3,'$NOTIFY_ADDR3'),";
            $field2 .= "NOTIFY_TEL,"                 ;       $value2 .= "ISNULL(NOTIFY_TEL,'$NOTIFY_TEL'),";
            $field2 .= "NOTIFY_FAX,"                 ;       $value2 .= "ISNULL(NOTIFY_FAX,'$NOTIFY_FAX'),";
            $field2 .= "NOTIFY_ATTN,"                ;       $value2 .= "ISNULL(NOTIFY_ATTN,'$NOTIFY_ATTN'),";
            $field2 .= "NOTIFY_NAME_2,"              ;       $value2 .= "ISNULL(NOTIFY_NAME_2,'$NOTIFY_NAME_2'),";
            $field2 .= "NOTIFY_ADDR1_2,"             ;       $value2 .= "ISNULL(NOTIFY_ADDR1_2,'$NOTIFY_ADDR1_2'),";
            $field2 .= "NOTIFY_ADDR2_2,"             ;       $value2 .= "ISNULL(NOTIFY_ADDR2_2,'$NOTIFY_ADDR2_2'),";
            $field2 .= "NOTIFY_ADDR3_2,"             ;       $value2 .= "ISNULL(NOTIFY_ADDR3_2,'$NOTIFY_ADDR3_2'),";
            $field2 .= "NOTIFY_TEL_2,"               ;       $value2 .= "ISNULL(NOTIFY_TEL_2,'$NOTIFY_TEL_2'),";
            $field2 .= "NOTIFY_FAX_2,"               ;       $value2 .= "ISNULL(NOTIFY_FAX_2,'$NOTIFY_FAX_2'),";
            $field2 .= "NOTIFY_ATTN_2,"              ;       $value2 .= "ISNULL(NOTIFY_ATTN_2,'$NOTIFY_ATTN_2'),";
            $field2 .= "EMKL_NAME,"                  ;       $value2 .= "ISNULL(EMKL_NAME,'$EMKL_NAME'),";
            $field2 .= "EMKL_ADDR1,"                 ;       $value2 .= "ISNULL(EMKL_ADDR1,'$EMKL_ADDR1'),";
            $field2 .= "EMKL_ADDR2,"                 ;       $value2 .= "ISNULL(EMKL_ADDR2,'$EMKL_ADDR2'),";
            $field2 .= "EMKL_ADDR3,"                 ;       $value2 .= "ISNULL(EMKL_ADDR3,'$EMKL_ADDR3'),";
            $field2 .= "EMKL_TEL,"                   ;       $value2 .= "ISNULL(EMKL_TEL,'$EMKL_TEL'),";
            $field2 .= "EMKL_FAX,"                   ;       $value2 .= "ISNULL(EMKL_FAX,'$EMKL_FAX'),";
            $field2 .= "EMKL_ATTN,"                  ;       $value2 .= "ISNULL(EMKL_ATTN,'$EMKL_ATTN'),";
            $field2 .= "SPECIAL_INFO"                ;       $value2 .= "ISNULL(SPECIAL_INFO,'$SPECIAL_INFO')";
            chop($field2);              			         chop($value2);

            $ins = "INSERT INTO si_header ($field2)
                (SELECT $value2 from si_header where si_no='$SI_NO_OLD') ";
            // echo $ins;
            $data_ins = sqlsrv_query($connect, $ins);
            
            if($data_ins === false ) {
                if(($errors = sqlsrv_errors() ) != null) {  
                    foreach( $errors as $error){  
                        $msg .= "message: ".$error[ 'message']."<br/>";  
                    }  
                }
            }
        }


        $exp_po = explode(', ', $CUST_SI_NO);
        $rowno = 1;
        // echo '<br/>';
        // echo count($exp_po);//[0];


        for ($i=0; $i < count($exp_po); $i++) { 
            if($exp_po[$i] != ''){
                $sql2 = "INSERT INTO si_po  (create_date,operation_date, si_no, line_no,po_no)
                VALUES (GETDATE(), SYSDATETIME(), '$SI_NO', $rowno , '$exp_po[$i]') ";
                // echo $sql2.'<br/>';
                $data_sql2 = sqlsrv_query($connect, strtoupper($sql2));
                if($data_sql2 === false ) {
                    if(($errors = sqlsrv_errors() ) != null) {  
                        foreach( $errors as $error){  
                            $msg .= "message: ".$error[ 'message']."<br/>";  
                        }  
                    }
                }
            }
            $rowno++;
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