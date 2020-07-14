<?php
	session_start();
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$slip_no = isset($_REQUEST['slip_no']) ? strval($_REQUEST['slip_no']) : '';
	$ck_slip = isset($_REQUEST['ck_slip']) ? strval($_REQUEST['ck_slip']) : '';
	$sts = isset($_REQUEST['wo_no']) ? strval($_REQUEST['wo_no']) : '';
	$ck_sts = isset($_REQUEST['ck_wo']) ? strval($_REQUEST['ck_wo']) : '';
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	$ck_item = isset($_REQUEST['ck_item']) ? strval($_REQUEST['ck_item']) : '';

	if ($ck_slip != "true"){
		$slip = "a.slip_no='$slip_no' and ";
	}else{
		$slip = "";
	}

	if ($ck_sts != "true"){
		$st = "b.wo_no='$wo_no' and ";
	}else{
		$st = "";
	}

	if ($ck_item != "true"){
		$item = "a.item_no='$item_no' and ";
	}else{
		$item = "";
	}

	$where ="where $slip $item to_char(a.wo_date,'YYYY-MM-DD') between '$date_awal' and '$date_akhir' and a.wo_date is not null";
	
	include("../connect/conn.php");

	$sql = "select 'MT-'||a.slip_no as slip_no, to_date(substr(a.tanggal,0,10),'mm/dd/yyyy') as tanggal,
		a.line_no, a.item_no, a.rack, a.pallet, a.qty, a.id, b.description, a.print, c.approval_date
		from ztb_wh_out_det a
		inner join item b on a.item_no=b.item_no
		left join mte_header c on 'MT-'||a.slip_no = c.slip_no
		$where
		order by a.wo_date asc, a.id_1 asc";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q);
		$s = $items[$rowno]->PRINT;
		if($s == 1){
			$items[$rowno]->STS_KIRIM = '<span style="color:blue;font-size:11px;"><b>SUDAH DI KIRIM</b></span>';
		}else{
			$items[$rowno]->STS_KIRIM = '<span style="color:red;font-size:11px;"><b>BELUM DI KIRIM</b></span>';
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