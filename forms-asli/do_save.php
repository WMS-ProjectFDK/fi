<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../connect/conn.php");

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
		$do_item = $query->do_item;			$do_wono = $query->do_wono;

		$now=date('Y-m-d H:i:s');				$now2=date('Y-m-d');					$user = $_SESSION['id_wms'];
	
		// INSERT MTE_HEADER
		$field  = "slip_no,"		;	$value  = "'".trim($do_slip)."',"				;
		$field .= "slip_date,"		;	$value .= "TO_DATE('$do_date','yyyy-mm-dd'),"	;
		$field .= "company_code,"	;	$value .= "".$do_comp.","						;
		$field .= "slip_type,"		;	$value .= "'".$do_type."',"						;
		$field .= "display_type,"	;	$value .= "'A',"								;
		$field .= "person_code,"	;	$value .= "'$user',"							;
		$field .= "section_code"	;	$value .= "100"									;
		chop($field);   				chop($value);
	
		$ins1 = "insert into mte_header ($field) select $value from dual where not exists (select * from mte_header where slip_no ='".trim($do_slip)."')";
		$data_ins1 = oci_parse($connect, $ins1);
		oci_execute($data_ins1);
		//echo $ins1;
		$pesan = oci_error($data_ins1);
		$msg .= $pesan['message'];

		if($msg != ''){
			$msg .= " SLIP HEADER Process Error : $ins1";
			break;
		}

		//INSERT MTE_DETAILS
		$field_dtl  = "slip_no,"			; $value_dtl  = "'".trim($do_slip)."',"				;
		$field_dtl .= "line_no,"			; $value_dtl .= "".$do_line.","						;
		$field_dtl .= "item_no,"			; $value_dtl .= "'".$do_item."',"					;
		$field_dtl .= "qty,"				; $value_dtl .= "'".$do_qqty."',"					;
		$field_dtl .= "uom_q,"				; $value_dtl .= "'".$do_uomq."',"					;
		$field_dtl .= "cost_process_code,"	; $value_dtl .= "'".$do_cost."',"					;
		$field_dtl .= "reg_date,"			; $value_dtl .= "TO_DATE('$now2','yyyy-mm-dd'),"	;
		$field_dtl .= "upto_date,"			; $value_dtl .= "TO_DATE('$now2','yyyy-mm-dd'),"	;
		$field_dtl .= "wo_no,"				; $value_dtl .= "'".$do_wono."',"					;
		$field_dtl .= "remark,"				; $value_dtl .= "'".$do_rmrk."',"					;
		$field_dtl .= "date_code"           ; $value_dtl .= "'".$do_d_co."'"					;
		chop($field_dtl); 					  chop($value_dtl);

		$ins2 = "insert into mte_details ($field_dtl) 
			select $value_dtl from dual where not exists (select * from mte_details where slip_no ='".trim($do_slip)."' AND item_no = ".$do_item.")";
		$data_ins2 = oci_parse($connect, $ins2);
		oci_execute($data_ins2);
		//echo $ins2;

		$pesan = oci_error($data_ins2);
		$msg .= $pesan['message'];

		if($msg != ''){
			$msg .= " SLIP-DETAILS Process Error : $ins2";
			break;
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