<?php
	session_start();
	$result = array();

	$wo = isset($_REQUEST['wo']) ? strval($_REQUEST['wo']) : '';

	include("../connect/conn.php");

	$rowno=0;
	$rs = "select ITEM_NO, ITEM_NAME, SLIP_NO, SLIP_DATE, SLIP_QUANTITY_PI as SLIP_QUANTITY, SLIP_PRICE, SLIP_QUANTITY_PI*SLIP_PRICE as SLIP_AMOUNT
		FROM ZTB_PRODUCTION_INCOME 
		WHERE WO_NO_pi='$wo'
		ORDER BY SLIP_NO ASC";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$q = $items[$rowno]->SLIP_QUANTITY;
		$p = $items[$rowno]->SLIP_PRICE;
		$a = $items[$rowno]->SLIP_AMOUNT;
		$items[$rowno]->SLIP_QUANTITY = number_format($q);
		$items[$rowno]->SLIP_PRICE = number_format($p,5);
		$items[$rowno]->SLIP_AMOUNT = number_format($a,5);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>