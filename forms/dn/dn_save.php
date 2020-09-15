<?php
header('Content-Type: text/plain; charset="UTF-8"');
// error_reporting(0);
session_start();
include("../../connect/conn.php");
$user = $_SESSION['id_wms'];
// $user_name = $_SESSION['id_wms'];

// dn_save.php?data=[{"dn_sts":"DETAILS","dn_no":"DN-COBA","dn_line":1,"dn_cust":"996130","dn_dn_date_add":"2020-09-13","dn_attn_add":"","dn_bank_add":"3","dn_shipment_add":"","dn_exfact_add":"","dn_signature_add":"AGUSMAN SURYA","dn_DO_NO":"454/FILR/20","dn_BL_DATE":"2020-09-01","dn_SHIP_NAME":"YM HARMONI V.328C \rMOL MANUVER V.053E","dn_ETA":"2020-10-08","dn_ETD":"2020-09-01","dn_AMT_O":"28827.27"},{"dn_sts":"DETAILS","dn_no":"DN-COBA","dn_line":2,"dn_cust":"996130","dn_dn_date_add":"2020-09-13","dn_attn_add":"","dn_bank_add":"3","dn_shipment_add":"","dn_exfact_add":"","dn_signature_add":"AGUSMAN SURYA","dn_DO_NO":"453/FILR/20","dn_BL_DATE":"2020-09-01","dn_SHIP_NAME":"SC MARA V. 0QY2KN\rAPL CHARLESTON V. 0TX6VE1PL","dn_ETA":"2020-09-28","dn_ETD":"2020-08-30","dn_AMT_O":"150512.48"},{"dn_sts":"DETAILS","dn_no":"DN-COBA","dn_line":3,"dn_cust":"996130","dn_dn_date_add":"2020-09-13","dn_attn_add":"","dn_bank_add":"3","dn_shipment_add":"","dn_exfact_add":"","dn_signature_add":"AGUSMAN SURYA","dn_DO_NO":"460/FILR/20","dn_BL_DATE":"2020-09-01","dn_SHIP_NAME":"SEASPAN EMINENCE V.105N","dn_ETA":"2020-09-15","dn_ETD":"2020-09-01","dn_AMT_O":"8710.72"},{"dn_sts":"DETAILS","dn_no":"DN-COBA","dn_line":4,"dn_cust":"996130","dn_dn_date_add":"2020-09-13","dn_attn_add":"","dn_bank_add":"3","dn_shipment_add":"","dn_exfact_add":"","dn_signature_add":"AGUSMAN SURYA","dn_DO_NO":"456/FILR/20","dn_BL_DATE":"2020-09-01","dn_SHIP_NAME":"YM HARMONY V. 328C  \rMEISHAN BRIDGE V. 011E","dn_ETA":"2020-10-04","dn_ETD":"2020-09-01","dn_AMT_O":"68398.64"},{"dn_sts":"HEADER","dn_no":"DN-COBA","dn_line":5,"dn_cust":"996130","dn_dn_date_add":"2020-09-13","dn_attn_add":"","dn_bank_add":"3","dn_shipment_add":"","dn_exfact_add":"","dn_signature_add":"AGUSMAN SURYA","dn_DO_NO":"457/FILR/20","dn_BL_DATE":"2020-09-01","dn_SHIP_NAME":"YM HARMONI V. 328C\rMEISHAN BRIDGE V. 011E ","dn_ETA":"2020-10-04","dn_ETD":"2020-09-01","dn_AMT_O":333}]

if (isset($_SESSION['id_wms'])){
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
	$msg = '';

	foreach($queries as $query){
        $dn_sts = $query->dn_sts;
        $dn_no = $query->dn_no;
        $dn_line = $query->dn_line;
        $dn_cust = $query->dn_cust;
        $dn_dn_date_add = $query->dn_dn_date_add;
        $dn_attn_add = $query->dn_attn_add;
        $dn_bank_add = $query->dn_bank_add;
        $dn_shipment_add = $query->dn_shipment_add;
        $dn_exfact_add = $query->dn_exfact_add;
        $dn_signature_add = $query->dn_signature_add;
        $dn_DO_NO = $query->dn_DO_NO;
        $dn_BL_DATE = $query->dn_BL_DATE;
        $dn_SHIP_NAME = $query->dn_SHIP_NAME;
        $dn_ETA = $query->dn_ETA;
        $dn_ETD = $query->dn_ETD;
        $dn_AMT_O = $query->dn_AMT_O;
        $dn_CURR_CODE = $query->dn_CURR_CODE;
        $dn_AMT_L = $query->dn_AMT_L;
        $dn_PO_NO_DESC = $query->dn_PO_NO_DESC;

		if($dn_sts == 'HEADER'){
            # INSERT DN HEADER
            
            $FIELD  = "DN_NO,";                 $VALUE  = "'$dn_no',";
            $FIELD .= "DN_DATE,";               $VALUE .= "'$dn_date',";
            $FIELD .= "CUSTOMER_CODE,";         $VALUE .= "$dn_cust,";
            $FIELD .= "ATTN,";                  $VALUE .= "'$dn_attn_add',";
            // $FIELD .= "DN_DESCRIPTION,";        $VALUE .= "'$dn_',";
            $FIELD .= "DATE_SHIPMENT,";         $VALUE .= "'$dn_shipment_add',";
            $FIELD .= "DATE_EX_FACTORY,";       $VALUE .= "'$dn_exfact_add',";
            // $FIELD .= "REMARK,";                $VALUE .= "'$dn_re',";
            // $FIELD .= "UPTO_DATE,";             $VALUE .= "'$dn_',";
            // $FIELD .= "REG_DATE,";              $VALUE .= "'$dn_',";
            $FIELD .= "BANK_SEQ,";              $VALUE .= "$dn_bank_add,";
            $FIELD .= "SIGNATURE_NAME";        $VALUE .= "'$dn_signature_add'";

            $ins1  = "insert into dn_header ($FIELD) select $VALUE";
            echo $ins1;
            // $data_ins1 = sqlsrv_query($connect, $ins1);
			// $pesan = sqlsrv_errors($data_ins1);
            // $msg .= $pesan['message'];
            
			// if($msg != ''){
			// 	$msg .= " PRF-Header Process Error  : $ins1";
			// 	break;
			// }

		}else{
            //INSERT DN DETAILS
            $field_dtl  = "DN_NO,";               $value_dtl = "'$dn_no',";
            $field_dtl .= "LINE_NO,";             $value_dtl = "$dn_line,";
            $field_dtl .= "INV_NO,";              $value_dtl = "'$dn_DO_NO',";
            $field_dtl .= "PO_NO_DESC,";          $value_dtl = "'$dn_PO_NO_DESC',";
            $field_dtl .= "SHIP_NAME,";           $value_dtl = "'$dn_SHIP_NAME',";
            $field_dtl .= "ETD,";                 $value_dtl = "'$dn_ETD',";
            $field_dtl .= "ETA,";                 $value_dtl = "'$dn_ETA',";
            $field_dtl .= "CURR_CODE,";           $value_dtl = "$dn_CURR_CODE,";
            $field_dtl .= "AMT_O,";               $value_dtl = "$dn_AMT_O,";
            $field_dtl .= "AMT_L,";               $value_dtl = "$dn_AMT_L,";
            $field_dtl .= "UPTO_DATE,";           $value_dtl = "GETDATE(),";
            $field_dtl .= "REG_DATE";             $value_dtl = "GETDATE()";
            chop($field_dtl) ;                  chop($value_dtl) ;

            $ins2 = "insert into dn_details ($field_dtl) VALUES ($value_dtl)";
            echo $ins2."<br/>";

            // $data_ins2 = sqlsrv_query($connect, $ins2);
            // $pesan = sqlsrv_errors($data_ins2);
            // $msg .= $pesan['message'];

            // if($msg != ''){
            //     $msg .= " PRF-Details Process Error : $ins2";
            //     break;
            // }
        }
	};

}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode("success");
}
?>