<?php
	session_start();
	$result = array();

	$po = isset($_REQUEST['po']) ? strval($_REQUEST['po']) : '';

	include("../connect/conn.php");

	$rowno=0;
	$rs = "select b.line_no, a.po_no, b.item_no, c.description, b.uom_q, d.unit_pl, b.qty, b.gr_qty, b.bal_qty, b.u_price, b.amt_o, b.amt_l, b.eta from po_header a
		inner join po_details b on a.po_no=b.po_no
		inner join item c on b.item_no=c.item_no
		inner join unit d on b.uom_q=d.unit_code
		where a.po_no='$po'
		order by b.line_no asc";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$q = $items[$rowno]->QTY;
		$bq = $items[$rowno]->BAL_QTY;
		$gq = $items[$rowno]->GR_QTY;
		$o = $items[$rowno]->AMT_O;
		$l = $items[$rowno]->AMT_L;
		$items[$rowno]->AMT_O = number_format($o,2);
		$items[$rowno]->AMT_L = number_format($l,2);
		$items[$rowno]->QTY = number_format($q,2);
		$items[$rowno]->BAL_QTY = number_format($bq,2);
		$items[$rowno]->GR_QTY = number_format($gq,2);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>