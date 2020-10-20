<?php
header('Content-Type: text/plain; charset="UTF-8"');
// error_reporting(0);
session_start();
include("../../connect/conn.php");
$user = $_SESSION['id_wms'];
// $user_name = $_SESSION['id_wms'];

if (isset($_SESSION['id_wms'])){
    $dn_no_h = isset($_REQUEST['dn_no_h']) ? strval($_REQUEST['dn_no_h']) : '';
    $dn_stage = isset($_REQUEST['dn_stage']) ? strval($_REQUEST['dn_stage']) : '';
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
    $msg = '';
    
    if($dn_stage == 'EDIT'){
        $del = "delete from dn_header where dn_no='".$dn_no_h."'";
        $data_del = sqlsrv_query($connect, $del);

        if( $data_del === false ) {
            if( ($errors = sqlsrv_errors() ) != null) {
                foreach($errors as $error){
                    $msg .= $error[ 'message']; 
                }
            }
        }

        if($msg != ''){
            $msg .= " Delete-Header Process Error : $del";
            //break;
        }

        $del2 = "delete from dn_details where dn_no='".$dn_no_h."'";
        $data_del2 = sqlsrv_query($connect, $del2);

        if( $data_del2 === false ) {
            if( ($errors = sqlsrv_errors() ) != null) {
                foreach($errors as $error){
                    $msg .= $error[ 'message']; 
                }
            }
        }

        if($msg != ''){
            $msg .= " Delete-Details Process Error : $del2";
            //break;
        }
    }

	foreach($queries as $query){
        $dn_sts = $query->dn_sts;
        $dn_no = $query->dn_no;
        $dn_line = $query->dn_line;
        $dn_cust = $query->dn_cust;
        $dn_date = $query->dn_date;
        $dn_attn = $query->dn_attn;
        $dn_bank = $query->dn_bank;
        $dn_ship = $query->dn_ship;
        $dn_exfa = $query->dn_exfa;
        $dn_sign = $query->dn_sign;
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
            $FIELD .= "ATTN,";                  $VALUE .= "'$dn_attn',";
            // $FIELD .= "DN_DESCRIPTION,";        $VALUE .= "'$dn_',";
            $FIELD .= "DATE_SHIPMENT,";         $VALUE .= "'$dn_ship',";
            $FIELD .= "DATE_EX_FACTORY,";       $VALUE .= "'$dn_exfa',";
            $FIELD .= "UPTO_DATE,";             $VALUE .= "GETDATE(),";
            $FIELD .= "REG_DATE,";              $VALUE .= "GETDATE(),";
            $FIELD .= "BANK_SEQ,";              $VALUE .= "$dn_bank,";
            $FIELD .= "SIGNATURE_NAME,";        $VALUE .= "'$dn_sign',";
            $FIELD .= "REMARK";                 $VALUE .= "description";

            $ins1  = "insert into dn_header ($FIELD) 
                select $VALUE from DN_PAYMENT_TERMS where CUSTOMER_CODE = $dn_cust ";
            // echo $ins1.'<br/>';
            $data_ins1 = sqlsrv_query($connect, $ins1);
			if($data_ins1 === false ) {
                if(($errors = sqlsrv_errors() ) != null) {  
                     foreach( $errors as $error){  
                        $msg .= "message: ".$error[ 'message']."<br/>";  
                     }  
                }
            }
		}else{
            //INSERT DN DETAILS
            $field_dtl  = "DN_NO,";               $value_dtl  = "'$dn_no',";
            $field_dtl .= "LINE_NO,";             $value_dtl .= "$dn_line,";
            $field_dtl .= "INV_NO,";              $value_dtl .= "'$dn_DO_NO',";
            $field_dtl .= "PO_NO_DESC,";          $value_dtl .= "'$dn_PO_NO_DESC',";
            $field_dtl .= "SHIP_NAME,";           $value_dtl .= "'$dn_SHIP_NAME',";
            $field_dtl .= "ETD,";                 $value_dtl .= "'$dn_ETD',";
            $field_dtl .= "ETA,";                 $value_dtl .= "'$dn_ETA',";
            $field_dtl .= "CURR_CODE,";           $value_dtl .= "$dn_CURR_CODE,";
            $field_dtl .= "AMT_O,";               $value_dtl .= "$dn_AMT_O,";
            $field_dtl .= "AMT_L,";               $value_dtl .= "$dn_AMT_L,";
            $field_dtl .= "UPTO_DATE,";           $value_dtl .= "GETDATE(),";
            $field_dtl .= "REG_DATE";             $value_dtl .= "GETDATE()";
            chop($field_dtl) ;                    chop($value_dtl) ;

            $ins2 = "insert into dn_details ($field_dtl) VALUES ($value_dtl)";
            // echo $ins2."<br/>";
            $data_ins2 = sqlsrv_query($connect, $ins2);
            if($data_ins2 === false ) {
                if(($errors = sqlsrv_errors() ) != null) {  
                     foreach( $errors as $error){  
                        $msg .= "message: ".$error[ 'message']."<br/>";  
                     }  
                }
            }
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