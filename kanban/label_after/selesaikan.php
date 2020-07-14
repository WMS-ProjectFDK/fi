<?php
	header('Content-Type: text/plain; charset="UTF-8"');
	session_start();
	include("../../connect/conn.php");

	if (isset($_SESSION['user_labelAfter'])){
		$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
		$dt = json_decode(json_encode($data));
		$str = preg_replace('/\\\\\"/',"\"", $dt);
		$queries = json_decode($str);
		$user = $_SESSION['user_labelAfter'];
		$now = date('Y-m-d H:i:s');
		$msg = '';

		foreach($queries as $query){
			$batt_sts = $query->batt_sts;
			$batt_id = $query->batt_id;
			$batt_process = $query->batt_process;
			$batt_ng_desc = $query->batt_ng_desc;
			$batt_ng_qty = $query->batt_ng_qty;

			if ($batt_sts == 'HEADER') {
				$sql = "update ztb_lbl_trans set remark='RESULT', qty_terpakai=0,ng_qty=0 where rowid='$batt_id' and remark='FINISH' ";
				$result = oci_parse($connect, $sql);
				oci_execute($result);
				$pesan = oci_error($result);
				$msg = $pesan['message'];

				if($msg != ''){
					$msg .= " Update status kanban Error : $sql";
					break;
				}
			}/*else{		//insert ng details
				$ins = "insert into ztb_lbl_trans_ng (id_print, id_worker, process, description, qty, upto_date) 
					values ($batt_id, $user, '$batt_process', '$batt_ng_desc', $batt_ng_qty, '$now')";
				$result2 = oci_parse($connect, $ins);
				oci_execute($result2);
				$pesan = oci_error($result2);
				$msg = $pesan['message'];

				if($msg != ''){
					$msg .= " Insert Data NG error : $ins";
					break;
				}
			}*/
		}
	}else{
		$msg .= 'Session Expired';
	}


	if($msg == ''){
		echo json_encode('success');
	}else{
		echo json_encode($msg);
	}
?>