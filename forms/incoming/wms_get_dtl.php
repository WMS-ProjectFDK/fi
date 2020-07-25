<?php
	session_start();
	$result = array();

	$gr = isset($_REQUEST['gr']) ? strval($_REQUEST['gr']) : '';
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$line = isset($_REQUEST['line']) ? strval($_REQUEST['line']) : '';

	include("../../connect/conn.php");

	$rowno=0;
	$rs = "select a.item_no, b.description, a.pallet, a.qty from ztb_wh_in_det a inner join item b on a.item_no = b.item_no
		where gr_no='$gr' and a.item_no='$item' and a.line_no='$line' order by a.pallet asc";
	$data = sqlsrv_query($connect, $rs);
	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>