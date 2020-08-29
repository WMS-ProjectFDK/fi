<?php
session_start();
ini_set('max_execution_time', -1);
include("../../connect/conn.php");


if (isset($_SESSION['id_wms'])){
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
    $msg = '';

    foreach($queries as $query){
		$company_code = $query->company_code;
		$company_type = $query->company_type;
        $company_name = $query->company_name;
        $ADDRESS1 = $query->ADDRESS1;
		$ADDRESS2 = $query->ADDRESS2;
        $ADDRESS3 = $query->ADDRESS3;
        $ADDRESS4 = $query->ADDRESS4;
		$ATTN = $query->ATTN;
        $TEL_NO = $query->TEL_NO;
        $FAX_NO = $query->FAX_NO;
		$ZIP_CODE = $query->ZIP_CODE;
        $COUNTRY_CODE = $query->COUNTRY_CODE;
        $CURR_CODE = $query->CURR_CODE;
		$TTERMS = $query->TTERMS;
        $PDAYS = $query->PDAYS;
        $CASE_MARK = $query->CASE_MARK;
		$EDI_CODE = $query->EDI_CODE;
        $VAT = $query->VAT;
        $SUPPLY_TYPE = $query->SUPPLY_TYPE;
		$SUBC_CODE = $query->SUBC_CODE;
        $TRANSPORT_DAYS = $query->TRANSPORT_DAYS;
        

        $CC = $query->CC;
        $COMPANY_SHORT = $query->COMPANY_SHORT;
        $TAXPAYER_NO = $query->TAXPAYER_NO;
		$EMAIL = $query->EMAIL;
        $QUOT_SALE_CODE = $query->QUOT_SALE_CODE;
        $FORECAST_FLG = $query->FORECAST_FLG;
		$ACCPAC_COMPANY_CODE = $query->ACCPAC_COMPANY_CODE;
        $BONDED_TYPE = $query->BONDED_TYPE;
        
        $BC_DOC = $query->BC_DOC;
		$BC_DOC_REVERSE = $query->BC_DOC_REVERSE;


        $sql  = " 
        update COMPANY set 
               upto_Date = getdate(),
               COMPANY = '$company_name',
               ADDRESS1 = '$ADDRESS1',
               ADDRESS2 = '$ADDRESS2',
               ADDRESS3 = '$ADDRESS3',
               ADDRESS4 = '$ADDRESS4',
               ATTN = '$ATTN',
               TEL_NO = '$TEL_NO',
               FAX_no = '$FAX_NO',
               ZIP_CODE = '$ZIP_CODE',
               COUNTRY_CODE = '$COUNTRY_CODE',
               CURR_CODE = '$CURR_CODE',
               TTERMS = '$TTERMS',
               PDAYS = '$PDAYS',
               PDESC = '$PDESC',
               CASE_MARK = '$CASE_MARK',
               EDI_CODE  = '$EDI_CODE',
               VAT = '$VAT',
               SUPPLY_TYPE = '$SUPPLY_TYPE',
               SUBC_CODE = '$SUBC_CODE',
               TRANSPORT_DAYS =  '$TRANSPORT_DAYS',
               CC = '$CC',
               COMPANY_SHORT = '$COMPANY_SHORT',
               TAXPAYER_NO = '$TAXPAYER_NO',
               EMAIL = '$EMAIL',
               QUOT_SALE_CODE = '$QUOT_SALE_CODE',
               FORECAST_FLG = '$FORECAST_FLG',
               ACCPAC_COMPANY_CODE = '$ACCPAC_COMPANY_CODE',
               BONDED_TYPE = '$BONDED_TYPE',
               BC_DOC = '$BC_DOC',
               BC_DOC_REVERSE = '$BC_DOC_REVERSE'
        where company_code = $company_code
       " ;

        $data_save = sqlsrv_query($connect, strtoupper($sql));
        if($data_save === false ) {
            if(($errors = sqlsrv_errors() ) != null) {  
                foreach( $errors as $error){  
                    $msg .= "message: ".$error[ 'message']."<br/>";  
                }  
            }
        }

        // $field .= "  contract_seq,"  ;
        // $field .= "  curr_code,"     ;
        // $field .= "  pmethod,"       ;
        // $field .= "  pdays,"         ;
        // $field .= "  pdesc,"         ;
        // $field .= "  tterm,"         ;
        // $field .= "  tdesc,"         ;
        // $field .= "  loading_port,"  ;
        // $field .= "  discharge_port,";
        // $field .= "  notify_code,"   ;
        // $field .= "  consignee_code,"   ;
        // $field .= "  marks,"         ;
        // $field .= "  port_loading_code,"         ;
        // $field .= "  port_discharge_code,"         ;
        // $field .= "  final_destination_code,"         ;
        // $field .= "  upto_date,"     ;
       
        // $sql .= "   1,"  ;
        // $sql .= "  '$CURR_CODE',"     ;
        // $sql .= "  '$PMETHOD',"       ;
        // $sql .= "  '$PDAYS',"         ;
        // $sql .= "  '$PDESC',"         ;
        // $sql .= "  '$TTERMS',"         ;
        // $sql .= "  '$TDESC',"         ;
        // $sql .= "  '$LOADING_PORT',"  ;
        // $sql .= "  '$DISCHARGE_PORT',";
        // $sql .= "  '$NOTIFY_CODE',"   ;
        // $sql .= "  '$CONSIGNEE_CODE',";
        // $sql .= "  '$MARKS',"         ;
        // $sql .= "  '$PORT_LOADING_CODE',"         ;
        // $sql .= "  '$PORT_DISCHARGE_CODE',"         ;
        // $sql .= "  '$FINAL_DESTINATION_CODE',"         ;
        // $sql .= "  getdate(),"          ;
        // $sql .= "  getdate()"          ;
        // $sql .= " ) " ;


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
