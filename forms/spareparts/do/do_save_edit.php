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
		$do_item = $query->do_item;			$do_wono = $query->do_wono;				$do_subj = $query->do_subj;
		$do_lins = $query->do_lins;	

		$now=date('Y-m-d H:i:s');			      $user = $_SESSION['id_wms'];					  $now2=date('Y-m-d');

		$cek ="select cast(slip_date as varchar(10)) as SLIP_DATE from sp_mte_header where slip_no='$do_slip'";
		$data = sqlsrv_query($connect, $cek);
		if( $data === false ) {
			if( ($errors = sqlsrv_errors() ) != null) {
				foreach( $errors as $error ) {
					echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
					echo "code: ".$error[ 'code']."<br />";
					echo "message: ".$error[ 'message']."<br />";
					echo $cek;
				}
			}
		}
		$dt = sqlsrv_fetch_object($data);

		if($dt->SLIP_DATE != $do_date){
			$upd = "update sp_mte_header set slip_date = '$do_date', person_code='$user' where slip_no='$do_slip'";
			$data_upd = sqlsrv_query($connect, $upd);
			if( $data_upd === false ) {
				if( ($errors = sqlsrv_errors() ) != null) {
					foreach( $errors as $error ) {
						echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
						echo "code: ".$error[ 'code']."<br />";
						echo "message: ".$error[ 'message']."<br />";
						echo $data_upd;
					}
				}
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
			$field_dtl .= "reg_date,"			; $value_dtl .= "'$now2',"	;
			$field_dtl .= "upto_date,"			; $value_dtl .= "'$now2',"	;
			$field_dtl .= "wo_no,"				; $value_dtl .= "'".$do_wono."',"					;
			$field_dtl .= "remark"				; $value_dtl .= "'".$do_rmrk."'"					;
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
		}else{
			//UPDATE MTE_DETAILS
			$field_dtl = "update sp_mte_details set "						;
			$field_dtl .= "qty=".$do_qqty.","								;
			$field_dtl .= "cost_process_code='".$do_subj."',"				;
			$field_dtl .= "reg_date='".$now2."',"							;
			$field_dtl .= "upto_date='".$now2."',"							;
			$field_dtl .= "wo_no='".$do_wono."',"							;
			$field_dtl .= "remark='".$do_rmrk."',"							;
			$field_dtl .= "date_code='".$do_d_co."'"						;
			chop($field_dtl);
			$field_dtl .= " where slip_no='".trim($do_slip)."' and item_no='".$do_item."' ";
			
			$data_ins2 = sqlsrv_query($connect, $field_dtl);
			if( $data_ins2 === false ) {
				if( ($errors = sqlsrv_errors() ) != null) {
					foreach( $errors as $error ) {
						echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
						echo "code: ".$error[ 'code']."<br />";
						echo "message: ".$error[ 'message']."<br />";
						echo $field_dtl;
					}
				}
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