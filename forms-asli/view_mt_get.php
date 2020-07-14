<?php
	session_start();
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$slip_no = isset($_REQUEST['slip_no']) ? strval($_REQUEST['slip_no']) : '';
	$ck_slip = isset($_REQUEST['ck_slip']) ? strval($_REQUEST['ck_slip']) : '';
	$wo_no = isset($_REQUEST['wo_no']) ? strval($_REQUEST['wo_no']) : '';
	$ck_wo = isset($_REQUEST['ck_wo']) ? strval($_REQUEST['ck_wo']) : '';
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	$ck_item = isset($_REQUEST['ck_item']) ? strval($_REQUEST['ck_item']) : '';

	if ($ck_slip != "true"){
		$slip = "a.slip_no='$slip_no' and ";
	}else{
		$slip = "";
	}

	if ($ck_wo != "true"){
		$wo = "b.wo_no='$wo_no' and ";
	}else{
		$wo = "";
	}

	if ($ck_item != "true"){
		$item = "a.item_no='$item_no' and ";
	}else{
		$item = "";
	}

	$where ="where $slip $wo $item to_char(d.slip_date,'YYYY-MM-DD') between '$date_awal' and '$date_akhir'";
	
	include("../connect/conn.php");

	$sql = "select distinct a.id,b.item_no as upper_item,b.brand,b.wo_no,b.plt_no, a.slip_no,d.slip_date,
		case when d.approval_date IS NULL then 'BELUM DI APPROVE' else 'SUDAH DI APPROVE' end as sts
		from ztb_wh_kanban_trans a
		inner join ztb_m_plan b on a.id=b.id
		inner join item c on a.item_no=c.item_no
		inner join mte_header d on a.slip_no= d.slip_no
		inner join unit e on c.uom_q= e.unit_code
		$where
		order by b.wo_no asc, b.plt_no asc";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$s = $items[$rowno]->STS; 
		if($s == 'SUDAH DI APPROVE'){
			$items[$rowno]->STS_APPROVE = '<span style="color:blue;font-size:11px;"><b>SUDAH DI APPROVE</b></span>';
		}else{
			$items[$rowno]->STS_APPROVE = '<span style="color:red;font-size:11px;"><b>BELUM DI APPROVE</b></span>';
		}
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>