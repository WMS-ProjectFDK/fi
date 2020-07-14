<?php
	session_start();
	$result = array();

	$prf = isset($_REQUEST['prf']) ? strval($_REQUEST['prf']) : '';

	include("../../connect/conn.php");

	$rowno=0;
	$rs = "select a.line_no, a.item_no, b.description, a.uom_q, c.unit_pl, a.estimate_price, a.require_date, a.qty, a.amt, a.ohsas, 
		isnull(pod.po_no,'-') as po_no, isnull(pod.qty,0) as po_qty,a.qty - isnull(pod.qty,0)  as ost
		from prf_details a
		inner join item b on a.item_no = b.item_no
		inner join unit c on a.uom_q = c.unit_code
		left join po_details pod on a.prf_no=pod.prf_no and a.line_no=pod.prf_line_no
		where a.prf_no='$prf' order by a.line_no asc";
	$data = sqlsrv_query($connect, $rs);

	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q);

		$qpo = $items[$rowno]->PO_QTY;
		$items[$rowno]->PO_QTY = number_format($qpo);

		$qost = $items[$rowno]->OST;
		$items[$rowno]->OST = number_format($qost);
		
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>