<?php
	session_start();
	$result = array();
	
	$prf_no = isset($_REQUEST['prf_no']) ? strval($_REQUEST['prf_no']) : '';

	include("../connect/conn.php");
	$rowno=0;
	$rs = "select a.line_no, a.item_no, b.description, a.uom_q, c.unit_pl as unit, a.estimate_price, to_char(a.require_date,'yyyy-mm-dd') as require_date,
		a.qty, a.amt, a.ohsas from prf_details a
		inner join item b on a.item_no = b.item_no
		inner join unit c on a.uom_q = c.unit_code
		where a.prf_no='$prf_no' order by a.line_no asc";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>