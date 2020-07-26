<?php
	ini_set('max_execution_time', -1);
	session_start();
	//item_no=1170117&tgl_plan=2017-11-26
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	$tgl_plan = isset($_REQUEST['tgl_plan']) ? strval($_REQUEST['tgl_plan']) : '';

	include("../../../connect/conn.php");

	$cek = "select * from 
		(select b.prf_no, a.line_no, cast(b.prf_date as varchar(10)) prf_date, a.item_no, i.item, i.description, a.uom_q, u.unit, a.estimate_price, a.qty 
		 from prf_details a
		 inner join prf_header b on a.prf_no=b.prf_no
		 inner join item i on a.item_no=i.item_no
		 inner join unit u on a.uom_q = u.unit_code
		 where a.item_no = $item_no AND a.require_date = '$tgl_plan'
		)aa
		left outer join 
		(select po_no, line_no as po_line, qty as qty_po, cast(eta as varchar) as eta, prf_no as prf, prf_line_no as prf_line from po_details)bb on aa.prf_no = bb.prf AND aa.line_no = bb.prf_line" ;
	$data_cek = sqlsrv_query($connect, strtoupper($cek));
	$items = array();
	$rowno=0;
	// echo $cek;

	while($row = sqlsrv_fetch_object($data_cek)){
		array_push($items, $row);
		$Q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = '<b>'.number_format($Q).'</b>';

		$QPO = $items[$rowno]->QTY_PO;
		$items[$rowno]->QTY_PO = number_format($QPO);

		$PO = $items[$rowno]->PO_NO;
		$PL = $items[$rowno]->PO_LINE;
		$items[$rowno]->PO_NO = $PO.' ('.$PL.')';
		$rowno++;
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
?>