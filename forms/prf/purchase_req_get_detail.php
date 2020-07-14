<?php
	session_start();
	$result = array();

	$prf = isset($_REQUEST['prf']) ? strval($_REQUEST['prf']) : '';

<<<<<<< HEAD
	include("../connect/conn.php");

	$rowno=0;
	$rs = "select a.line_no, a.item_no, b.description, a.uom_q, c.unit_pl, a.estimate_price, a.require_date, a.qty, a.amt, a.ohsas, 
		nvl(pod.po_no,'-') as po_no, nvl(pod.qty,0) as po_qty,a.qty - nvl(pod.qty,0)  as ost
=======
	include("../../connect/conn.php");

	$rowno=0;
	$rs = "select a.line_no, a.item_no, b.description, a.uom_q, c.unit_pl, a.estimate_price, a.require_date, a.qty, a.amt, a.ohsas, 
		isnull(pod.po_no,'-') as po_no, isnull(pod.qty,0) as po_qty,a.qty - isnull(pod.qty,0)  as ost
>>>>>>> 77172d8c738f23e29278a5ce17a9606a9260d23e
		from prf_details a
		inner join item b on a.item_no = b.item_no
		inner join unit c on a.uom_q = c.unit_code
		left join po_details pod on a.prf_no=pod.prf_no and a.line_no=pod.prf_line_no
		where a.prf_no='$prf' order by a.line_no asc";
<<<<<<< HEAD
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
=======
	$data = sqlsrv_query($connect, $rs);

	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
>>>>>>> 77172d8c738f23e29278a5ce17a9606a9260d23e
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