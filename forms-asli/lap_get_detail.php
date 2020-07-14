<?php
	session_start();
	$result = array();

	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$wo = isset($_REQUEST['wo']) ? strval($_REQUEST['wo']) : '';
	$dt_a = isset($_REQUEST['dt_a']) ? strval($_REQUEST['dt_a']) : '';
	$dt_z = isset($_REQUEST['dt_a']) ? strval($_REQUEST['dt_a']) : '';

	include("../connect/conn.php");

	$rowno=0;
	$rs = "";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$q=$items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>