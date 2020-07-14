<?php
	header('Content-Type: text/plain; charset="UTF-8"');
	include("../../connect/conn.php");

	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
	$now = date('Y-m-d H:i:s');
	$msg = '';

	foreach($queries as $query){
		$batt_sts = $query->batt_sts;
		$batt_idNG = $query->batt_idNG;
		$batt_id = $query->batt_id;
		$batt_ln = $query->batt_ln;
		$batt_proc = $query->batt_proc;
		$batt_ng1 = $query->batt_ng1;
		$batt_qty1 = $query->batt_qty1;
		$batt_ng2 = $query->batt_ng2;
		$batt_qty2 = $query->batt_qty2;
		$batt_ng3 = $query->batt_ng3;
		$batt_qty3 = $query->batt_qty3;

		//insert ng details
		$ins = "insert into ztb_lbl_trans_ng (id,id_print, labelline, process, DESC_NG_LABEL_DISPOSE, QTY1, DESC_NG_ASSY_DISPOSE, QTY2, DESC_NG_LABEL_REUSE, QTY3, UPTO_DATE) 
			values ('$batt_idNG',$batt_id, replace('$batt_ln','-','#'), '$batt_proc', '$batt_ng1', $batt_qty1, '$batt_ng2', $batt_qty2,  '$batt_ng3', $batt_qty3, to_date('$now','YYYY-MM-DD HH24:MI:SS'))";
		$result2 = oci_parse($connect, $ins);
		oci_execute($result2);
		$pesan = oci_error($result2);
		$msg = $pesan['message'];

		if($msg != ''){
			$msg .= " Insert Data NG error : $ins";
			break;
		}
	}


	if($msg == ''){
		echo json_encode('success');
	}else{
		echo json_encode($msg);
	}
?>