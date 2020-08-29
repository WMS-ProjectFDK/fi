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
		$company_code = $query->company_no;
		$company_type = $query->company_type;
        $company_name = $query->company_name;
        $ADDRESS1 = $query->company_address1;
		$ADDRESS2 = $query->company_address2;
        $ADDRESS3 = $query->company_address3;
        $ADDRESS4 = $query->company_address4;
		$ATTN = $query->attn;
        $TEL_NO = $query->telno;
        $FAX_NO = $query->faxno;
		$ZIP_CODE = $query->zip_code;
        $COUNTRY_CODE = $query->country;
        $CURR_CODE = $query->currency;
		$TTERMS = $query->tterms;
        $PDAYS = $query->pday;
        $PDESC = $query->pdesc;
        $CASE_MARK = '';
		$EDI_CODE = '';
        $VAT = 0;
        $SUPPLY_TYPE = $query->supply_type;
		$SUBC_CODE = '';
        $TRANSPORT_DAYS = $query->days_trans;
        

        $CC = '';
        $COMPANY_SHORT = $query->company_short_name;
        $TAXPAYER_NO = $query->taxpayer;
		$EMAIL = $query->email;
        $QUOT_SALE_CODE = 0;
        $FORECAST_FLG = 0;
		$ACCPAC_COMPANY_CODE = $query->accpac;
        $BONDED_TYPE = $query->bonded;
        
        $BC_DOC = '';
        $BC_DOC_REVERSE = '';
        
        //CONTRACT 
        // $PMETHOD = $query->PMETHOD;
        // $TDESC = $query->TDESC;
        // $LOADING_PORT = $query->LOADING_PORT;
        // $DISCHARGE_PORT = $query->DISCHARGE_PORT;


        // $NOTIFY_CODE = $query->NOTIFY_CODE;
        // $TDESC = $query->TDESC;
        // $LOADING_PORT = $query->LOADING_PORT;
        // $DISCHARGE_PORT = $query->DISCHARGE_PORT;

        // $CONSIGNEE_CODE = $query->CONSIGNEE_CODE;
        // $MARKS = $query->MARKS;
        // $PORT_LOADING_CODE = $query->PORT_LOADING_CODE;
        // $PORT_DISCHARGE_CODE = $query->PORT_DISCHARGE_CODE;
        // $FINAL_DESTINATION_CODE = $query->FINAL_DESTINATION_CODE;

        


        $sql  = " 
        insert into COMPANY
        select getdate(),
            getdate(), 
            null ,
            $company_code,
            $company_type,
            '$company_name',
            '$ADDRESS1',
            '$ADDRESS2',
            '$ADDRESS3',
            '$ADDRESS4',
            '$ATTN',
            '$TEL_NO',
            '$FAX_NO',
            '$ZIP_CODE',
            '$COUNTRY_CODE',
            $CURR_CODE,
            '$TTERMS',
            $PDAYS,
            '$PDESC',
            '$CASE_MARK',
            '$EDI_CODE',
            '$VAT',
            '$SUPPLY_TYPE',
            '$SUBC_CODE',
            $TRANSPORT_DAYS,
            '$CC',
            '$COMPANY_SHORT',
            '$TAXPAYER_NO',
            '$EMAIL',
            '$QUOT_SALE_CODE',
            '$FORECAST_FLG',
            '$ACCPAC_COMPANY_CODE',
            '$BONDED_TYPE',
            '$BC_DOC',
            '$BC_DOC_REVERSE'" ;

        $data_save = sqlsrv_query($connect, strtoupper($sql));
        if($data_save === false ) {
            if(($errors = sqlsrv_errors() ) != null) {  
                foreach( $errors as $error){  
                    $msg .= "message: ".$error[ 'message']."<br/>";  
                }  
            }
        }

        // $sql  = "insert into contract("   ;
        // $sql .= "  company_code,"  ;
        // $sql .= "  contract_seq,"  ;
        // $sql .= "  curr_code,"     ;
        // $sql .= "  pmethod,"       ;
        // $sql .= "  pdays,"         ;
        // $sql .= "  pdesc,"         ;
        // $sql .= "  tterm,"         ;
        // $sql .= "  tdesc,"         ;
        // $sql .= "  loading_port,"  ;
        // $sql .= "  discharge_port,";
        // $sql .= "  notify_code,"   ;
        // $sql .= "  consignee_code,"   ;
        // $sql .= "  marks,"         ;
        // $sql .= "  port_loading_code,"         ;
        // $sql .= "  port_discharge_code,"         ;
        // $sql .= "  final_destination_code,"         ;
        // $sql .= "  upto_date,"     ;
        // $sql .= "  reg_date "      ;
        // $sql .= " ) values ( "        ;
        // $sql .= "  '$company_code',"  ;
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
	echo 'OK';
}

?>
