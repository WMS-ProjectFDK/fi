<?php
	ini_set('max_execution_time', -1);
	session_start();
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	$tgl_plan = isset($_REQUEST['tgl_plan']) ? strval($_REQUEST['tgl_plan']) : '';
	$exp = explode('-', $tgl_plan);

	include("../../../connect/conn.php");

	$cek = "select cast(a.item_no as varchar) + '-' + b.description as brand, a.work_order, a.lower_item_no, a.description, 
		cast(a.mps_date as varchar(20)) mps_date, a.mps_qty, a.konversi,cast(a.cr_Date as varchar(10)) as cr_date ,a.qty
		--, a.date_code,a.status 
		from zvw_mrp_pm_konversi a
		inner join item b on a.item_no = b.item_no
		where lower_item_no in ($item_no,70000000 + $item_no) and mps_date='$tgl_plan' 
		order by a.cr_date asc";
	$data_cek = sqlsrv_query($connect, strtoupper($cek));
	// echo $cek;
	$items = array();
	$rowno=0;

	while($row = sqlsrv_fetch_object($data_cek)){
		array_push($items, $row);
		$Q = $items[$rowno]->MPS_QTY;
		$items[$rowno]->MPS_QTY = number_format($Q);

		$R = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($R);

		$o = $items[$rowno]->KONVERSI;
		$items[$rowno]->KONVERSI = '<b>'.number_format($o).'</b>';

		$rowno++;
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
?>