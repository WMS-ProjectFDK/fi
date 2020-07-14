<?php
	session_start();
	$result = array();
	
	$prf_no = isset($_REQUEST['prf_no']) ? strval($_REQUEST['prf_no']) : '';

	include("../../connect/conn.php");
	$rowno=0;
	$rs = "select a.line_no, a.item_no, b.description, a.uom_q, c.unit_pl as unit, a.estimate_price, cast(a.require_date as varchar(10))  require_date,
		a.qty, a.amt, a.ohsas from prf_details a
		inner join item b on a.item_no = b.item_no
		inner join unit c on a.uom_q = c.unit_code
		where a.prf_no='$prf_no' order by a.line_no asc";
	$data = sqlsrv_query($connect, $rs);
	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>