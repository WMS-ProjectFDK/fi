<?php
	ini_set('max_execution_time', -1);
	session_start();
	//item_no=1170117&tgl_plan=2017-11-26
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	$tgl_plan = isset($_REQUEST['tgl_plan']) ? strval($_REQUEST['tgl_plan']) : '';
	$exp = explode('-', $tgl_plan);

	include("../connect/conn.php");

	$cek = "select a.item_no || '-' ||b.description as brand, a.work_order, a.lower_item_no, a.description, 
		a.mps_date, a.mps_qty, a.konversi,a.cr_Date ,a.qty, a.status, a.date_code
		from zvw_mrp_pm_konversi a
		inner join item b on a.item_no = b.item_no
		where lower_item_no in ($item_no,70000000 + $item_no) and to_char(mps_date,'yyyy-mm-dd')='$tgl_plan' 
		order by a.cr_date asc";
	$data_cek = oci_parse($connect, $cek);
	oci_execute($data_cek);
	$items = array();
	$rowno=0;

	while($row = oci_fetch_object($data_cek)){
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