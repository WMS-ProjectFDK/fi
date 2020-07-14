<?php
	session_start();
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$supp = isset($_REQUEST['supp']) ? strval($_REQUEST['supp']) : '';
	
	include("../connect/conn.php");

	$sql = "select a.po_no, a.po_date, b.line_no, b.item_no,c.item, c.description, b.qty, b.gr_qty, b.qty-b.gr_qty as balance  from po_header a
		inner join po_details b on a.po_no=b.po_no
		inner join item c on b.item_no=c.item_no
		where a.supplier_code='$supp' and b.item_no='$item' and b.qty-b.gr_qty > 0
		order by po_date asc";
	$data = oci_parse($connect, $sql);
	oci_execute($data);
	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$qpo = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($qpo);
		$qgr = $items[$rowno]->GR_QTY;
		$items[$rowno]->GR_QTY = number_format($qgr);
		$bal = $items[$rowno]->BALANCE;
		$items[$rowno]->BALANCE = number_format($bal);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>