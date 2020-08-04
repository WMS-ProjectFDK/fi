<?php
	session_start();
	$result = array();
	$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';
	include("../connect/conn.php");

	$rowno=0;
	$rs = "select a.id, a.item_no, c.item, c.description, a.qty, c.uom_q, e.unit
		from ztb_wh_kanban_trans a
		inner join item c on a.item_no=c.item_no
		inner join unit e on c.uom_q= e.unit_code
		where a.id='$id'
		order by a.item_no asc";
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