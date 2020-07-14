<?php
header('Content-Type: text/plain; charset="UTF-8"');
date_default_timezone_set('Asia/Jakarta');
//error_reporting(0);
session_start();
include("../../connect/conn.php");

if (isset($_SESSION['user_labelAfter'])){
	$user = $_SESSION['user_labelAfter'];
	$shift = $_SESSION['shift_labelAfter'];
	$labelLine = $_SESSION['line_labelAfter'];
	$now = date('Y-m-d H:i:s');

	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
	
	$msg = '';
	$items = array();	$hasil = array();
	$rowno=0;			$flag=0;

	foreach($queries as $query){
		$id_kanban = $query->id_kanban;
		$wo_no = $query->wo_no;
		$plt_no = $query->plt_no;
		$qty_prod = $query->qty_prod;
		$box_type = $query->box_type;
		$box_qty = $query->box_qty;
		$non_box_qty = $query->non_box_qty;
		$id_record = $query->id_record;
		$set_box_qty = $query->set_box_qty;
		$grade = $query->grade;
		$qty_in_act = $query->qty_in_act;

		$sql = "select a.id_print, a.status, 
			case when status = 2 then b.qty_antri else a.qty - (a.qty_terpakai+a.ng_qty)- b.qty_antri end as qty, 
			a.asy_line , a.grade, cast(a.ROWID as varchar(50)) ROW_ID
			from ztb_lbl_trans a
			inner join (select id_print, sum(qty_in_antrian) as qty_antri from ztb_lbl_trans group by id_print)b on a.id_print = b.id_print
			where (a.status = 0 OR a.status = 2) and a.remark='RESULT' 
			and a.qty - (a.qty_terpakai+ng_qty) > 0 
			and replace(a.labelline,'#','-') = '$labelLine' and a.grade = '$grade'
			order by to_date(a.recorddate,'YYYY-MM-DD HH24:MI:SS') asc";
		$data = oci_parse($connect, $sql);
		oci_execute($data);
		
		$pesan = oci_error($data);
		$msg = $pesan['message'];
		
		if($msg != ''){
			$msg .= " Cek semi battery IN Error : $sql";
			break;
		}else{
			$qtot = 0;
			while($row = oci_fetch_object($data)){
				if($flag == 0){
					array_push($items, $row);
					$id_print = $items[$rowno]->ID_PRINT;
					$id_row = $items[$rowno]->ROW_ID;
					$qOut = $items[$rowno]->QTY;
					
					if($qty_in_act > $qOut){
						$q = $qOut;
						$s = $qty_in_act - $qOut;
					}else{
						$q = $qty_in_act;
						$s = $qOut - $qty_in_act;
						$flag = 1;
					}

					$ins = "insert into ztb_lbl_trans_det (tanggal,shift,qty,id_print,wo_no,plt_no,NG_QTY) 
						values ('".date('Y-m-d H:i:s')."', '$shift', $q, '$id_print', '$wo_no', '$plt_no', 0)";
					$data_ins = oci_parse($connect, $ins);
					oci_execute($data_ins);
					
					$pesan = oci_error($data_ins);
					$msg = $pesan['message'];

					if($msg != ''){
						$msg .= " save semi battery detail Error : $ins";
						break;
					}else{
						$upd = "update ztb_lbl_trans set qty_terpakai = qty_terpakai+$q where rowid = '$id_row'";
						$data_upd = oci_parse($connect, $upd);
						oci_execute($data_upd);
						
						$pesan = oci_error($data_upd);
						$msg = $pesan['message'];

						if($msg != ''){
							$msg .= " update semi battery terpakai Error : $upd";
							break;
						}
					}

					$qty_in_act = $s;
					$qtot += $q;
					array_push($hasil, 'ASSYLINE:'.$items[$rowno]->ASY_LINE, 'ID:'.$id_print, 'QTY:'.$qOut);
				}
				$rowno++;
			}

			$assy = '';
			for ($i=0; $i < count($items); $i++) { 
				$assy += $items[$i]->ASY_LINE;
			}

			/* update ztb_kanban_lbl*/
			$upd_kanban = "update ztb_kanban_lbl set 
				asyline = '$assy',
				z_qty = '0' ,
				labelline = '".str_replace("-", "#", $labelLine)."',
				enddate = '$now',
				battery_IN = '$qtot',
				LOTNUMBER= '$now',
				BOXTYPE = '".strtoupper($box_type)."',
				QTY = $box_qty,
				QTYSISAKOTAK = $non_box_qty,
				QTYSISAPRODUKSI = 0 
				
			where IDKANBAN = $id_kanban 
			and worker_id = $user
			and ID = $id_record ";
			$data_upd_kanban = oci_parse($connect, $upd_kanban);
			oci_execute($data_upd_kanban);

			if($msg != ''){
				$msg .= " update finish kanban Error : $upd_kanban";
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
	//$result = $items;
	//echo json_encode($result);
}
?>