<?php
	session_start();
	$result = array();

	$slip = isset($_REQUEST['slip']) ? strval($_REQUEST['slip']) : '';
	include("../connect/conn.php");

	$rowno=0;
	$rs = "select a.slip_no, b.line_no,b.item_no, c.description,b.qty,b.uom_q,d.unit from mte_header a
		left join mte_details b on a.slip_no=b.slip_no left join item c on b.item_no=c.item_no
		left join unit d on b.uom_q=d.unit_code where a.slip_no='$slip' and c.item_no not between 1200000 and 1299999 order by b.line_no asc";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>