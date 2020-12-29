<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../../../connect/conn.php");

if (isset($_SESSION['id_wms'])){
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
	$msg = '';

	foreach($queries as $query){
		$do_slip = $query->do_slip;			$do_qqty = $query->do_qqty;
		$do_line = $query->do_line;			$do_uomq = $query->do_uomq;
		$do_date = $query->do_date;			$do_cost = $query->do_cost;
		$do_type = $query->do_type;			$do_d_co = $query->do_d_co;
		$do_comp = $query->do_comp;			$do_rmrk = $query->do_rmrk;
		$do_item = $query->do_item;			$do_wono = $query->do_wono;			$do_subj = $query->do_subj;

		$now=date('Y-m-d H:i:s');				$now2=date('Y-m-d');					$user = $_SESSION['id_wms'];
	
		// INSERT MTE_HEADER
		$field  = "slip_no,"			;	$value  = "'".trim($do_slip)."',"	;
		$field .= "slip_date,"			;	$value .= "'$do_date',"				;
		$field .= "cost_process_code,"	;	$value .= "'$do_comp',"				;
		$field .= "cp_section_code,"	;	$value .= "(select cp_section_code from SP_COSTPROCESS where COST_PROCESS_CODE='$do_comp'),";
		$field .= "slip_type,"			;	$value .= "'".$do_type."',"			;
		$field .= "display_type,"		;	$value .= "'A',"					;
		$field .= "person_code,"		;	$value .= "'$user',"				;
		$field .= "section_code"		;	$value .= "100"						;
		chop($field);   				chop($value);
	
		$ins1 = "insert into sp_mte_header ($field) 
			select top 1 $value from sp_mte_header
			where not exists (select * from sp_mte_header where slip_no ='".trim($do_slip)."')";
		$data_ins1 = sqlsrv_query($connect, $ins1);

		if( $data_ins1 === false ) {
			if( ($errors = sqlsrv_errors() ) != null) {
				foreach( $errors as $error ) {
					echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
					echo "code: ".$error[ 'code']."<br />";
					echo "message: ".$error[ 'message']."<br />";
					echo $ins1;
				}
			}
		}
		// echo $ins1;

		//INSERT MTE_DETAILS
		$field_dtl  = "slip_no,"			; $value_dtl  = "'".trim($do_slip)."',"				;
		$field_dtl .= "line_no,"			; $value_dtl .= "".$do_line.","						;
		$field_dtl .= "item_no,"			; $value_dtl .= "'".$do_item."',"					;
		$field_dtl .= "qty,"				; $value_dtl .= "'".$do_qqty."',"					;
		$field_dtl .= "uom_q,"				; $value_dtl .= "'".$do_uomq."',"					;
		$field_dtl .= "reg_date,"			; $value_dtl .= "'$now2',"							;
		$field_dtl .= "upto_date,"			; $value_dtl .= "'$now2',"							;
		$field_dtl .= "wo_no,"				; $value_dtl .= "'".$do_wono."',"					;
		$field_dtl .= "remark,"				; $value_dtl .= "'".$do_rmrk."',"					;
		$field_dtl .= "date_code,"          ; $value_dtl .= "'".$do_d_co."',"					;
		$field_dtl .= "cost_process_code"   ; $value_dtl .= "'".$do_subj."'"					;
		chop($field_dtl); 					  chop($value_dtl);

		$ins2 = "insert into sp_mte_details ($field_dtl) 
			select top 1 $value_dtl from sp_mte_details where not exists (select * from sp_mte_details where slip_no ='".trim($do_slip)."' AND item_no = '".$do_item."')";
		$data_ins2 = sqlsrv_query($connect, $ins2);
		if( $data_ins2 === false ) {
			if( ($errors = sqlsrv_errors() ) != null) {
				foreach( $errors as $error ) {
					echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
					echo "code: ".$error[ 'code']."<br />";
					echo "message: ".$error[ 'message']."<br />";
					echo $ins2;
				}
			}
		}
		// echo $ins2;
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