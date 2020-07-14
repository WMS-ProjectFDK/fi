<?php
	session_start();
	$date_awal_slip = isset($_REQUEST['date_awal_slip']) ? strval($_REQUEST['date_awal_slip']) : '';
	$date_akhir_slip = isset($_REQUEST['date_akhir_slip']) ? strval($_REQUEST['date_akhir_slip']) : '';
	$ck_slip_date = isset($_REQUEST['ck_slip_date']) ? strval($_REQUEST['ck_slip_date']) : '';
	$date_awal_scan = isset($_REQUEST['date_awal_scan']) ? strval($_REQUEST['date_awal_scan']) : '';
	$date_akhir_scan = isset($_REQUEST['date_akhir_scan']) ? strval($_REQUEST['date_akhir_scan']) : '';
	$ck_scan_date = isset($_REQUEST['ck_scan_date']) ? strval($_REQUEST['ck_scan_date']) : '';
	$slip_no = isset($_REQUEST['slip_no']) ? strval($_REQUEST['slip_no']) : '';
	$ck_slip = isset($_REQUEST['ck_slip']) ? strval($_REQUEST['ck_slip']) : '';
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	$ck_item = isset($_REQUEST['ck_item']) ? strval($_REQUEST['ck_item']) : '';
	$wo_no = isset($_REQUEST['wo_no']) ? strval($_REQUEST['wo_no']) : '';
	$ck_wo = isset($_REQUEST['ck_wo']) ? strval($_REQUEST['ck_wo']) : '';

	if ($ck_slip_date != "true"){
		$slip_date = "to_char(a.slip_date,'YYYY-MM-DD') between '$date_awal_slip' and '$date_akhir_slip' AND ";
	}else{
		$slip_date = "";
	}

	if ($ck_scan_date != "true"){
		$scan_date = "to_char(b.date_in,'YYYY-MM-DD') between '$date_awal_scan' and '$date_akhir_scan' AND ";
	}else{
		$scan_date = "";
	}

	if ($ck_slip != "true"){
		$slip = "a.slip_no='$slip_no' and ";
	}else{
		$slip = "";
	}

	if ($ck_item != "true"){
		$item = "a.item_no='$item_no' and ";
	}else{
		$item = "";
	}

	if ($ck_wo != "true"){
		$wo = "a.wo_no='$wo_no' and ";
	}else{
		$wo = "";
	}

	$where ="where $slip_date $scan_date $slip $item $wo a.slip_no is not null";
	
	include("../connect/conn.php");

	$sql = "select * from (select a.slip_no, a.slip_date, a.item_no, a.item_name, a.item_description, 
		a.approval_date, a.slip_quantity, a.wo_no, c.plt_no,
		nvl(to_char(b.date_in),'BELUM DI SCAN') as scan
		from production_income a
		left join (select slip_no, date_in from ztb_wh_kanban_trans_fg) b on a.slip_no = b.slip_no
	    left join (select to_char(id) as id, wo_no, plt_no from ztb_p_plan) c on a.slip_no = c.id
		$where
		order by a.slip_date desc)
		where rownum<=250 ";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){	
		array_push($items, $row);
		$q = $items[$rowno]->SLIP_QUANTITY;
		$items[$rowno]->SLIP_QUANTITY = number_format($q);

		$s = $items[$rowno]->SCAN;
		if($s == 'BELUM DI SCAN'){
			$items[$rowno]->SCAN = '<span style="color:red;font-size:11px;"><b>BELUM DI SCAN</b></span>';
		}else{
			$items[$rowno]->SCAN = '<span style="color:blue;font-size:11px;"><b>'.$items[$rowno]->SCAN.'</b></span>';
		}

		$a = $items[$rowno]->APPROVAL_DATE;
		if(!is_null($a)){
			$items[$rowno]->STS_APPROVE = '<span style="color:blue;font-size:11px;"><b>SUDAH DI APPROVE</b></span>';
		}else{
			$items[$rowno]->STS_APPROVE = '<span style="color:red;font-size:11px;"><b>BELUM DI APPROVE</b></span>';
		}		
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>