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
		$do_lins = $query->do_lins;	

		$now=date('Y-m-d H:i:s');			      $user = $_SESSION['id_wms'];					  $now2=date('Y-m-d');

		$cek ="select TO_CHAR(slip_date,'yyyy-mm-dd') as slip_date from mte_header where slip_no='$do_slip'";
		$data = oci_parse($connect, $cek);
		oci_execute($data);
		$dt = oci_fetch_object($data);

		if($dt->SLIP_DATE != $do_date){
			$upd = "update mte_header set slip_date = TO_DATE('$do_date','yyyy-mm-dd'), person_code='$user' where slip_no='$do_slip'";
			$data_upd = oci_parse($connect, $upd);
			oci_execute($data_upd);
			$pesan = oci_error($data_upd);
			$msg .= $pesan['message'];

			if($msg != ''){
				$msg .= " Update Header Process Error : $upd";
				break;
			}
		}

		if($do_lins==''){
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
			$field_dtl .= "remark"				; $value_dtl .= "'".$do_rmrk."'"					;
			chop($field_dtl); 					  chop($value_dtl);

			$ins2 = "insert into mte_details ($field_dtl) 
				select $value_dtl from dual where not exists (select * from mte_details where slip_no ='".trim($do_slip)."' AND item_no = ".$do_item.")";
			$data_ins2 = oci_parse($connect, $ins2);
			oci_execute($data_ins2);
			//echo $ins2;
			$pesan = oci_error($data_ins1);
			$msg .= $pesan['message'];

			if($msg != ''){
				$msg .= " New Slip Details Process Error : $ins2";
				break;
			}
		}else{
			//UPDATE MTE_DETAILS
			$field_dtl = "update mte_details set "					;
			$field_dtl .= "qty=".$do_qqty.","								;
			$field_dtl .= "cost_process_code='".$do_cost."',"				;
			$field_dtl .= "reg_date=TO_DATE('".$now2."','yyyy-mm-dd'),"	;
			$field_dtl .= "upto_date=TO_DATE('".$now2."','yyyy-mm-dd'),"	;
			$field_dtl .= "wo_no='".$do_wono."',"							;
			$field_dtl .= "remark='".$do_rmrk."',"						;
			$field_dtl .= "date_code='".$do_d_co."'"						;
			chop($field_dtl);
			$field_dtl .= " where slip_no='".trim($do_slip)."' and item_no='".$do_item."' ";
			
			$data_ins2 = oci_parse($connect, $field_dtl);
			oci_execute($data_ins2);
			//echo $field_dtl;
			$pesan = oci_error($data_ins2);
			$msg .= $pesan['message'];

			if($msg != ''){
				$msg .= " Update Slip Details Process Error : $field_dtl";
				break;
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