<?php
	session_start();
	$result = array();

	$req = isset($_REQUEST['req']) ? strval($_REQUEST['req']) : '';

	include("../../connect/conn.php");

	$rowno=0;
	$rs = "select a.slip_no, b.line_no,b.item_no, c.description,b.qty,b.uom_q,d.unit,0 as sts, wh.this_inventory as inventory
		from mte_header a
		left join mte_details b on a.slip_no=b.slip_no 
		left join item c on b.item_no=c.item_no
		left join unit d on b.uom_q=d.unit_code 
		left join whinventory wh on b.item_no = wh.item_no
		where a.slip_no='$req'
		order by b.line_no asc";
	$data = sqlsrv_query($connect, strtoupper($rs));
	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q);

		$q_inv = $items[$rowno]->INVENTORY;
		$items[$rowno]->INVENTORY = number_format($q_inv);

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>