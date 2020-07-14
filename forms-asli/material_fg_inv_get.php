<?php
	session_start();
	$cmbBln = isset($_REQUEST['cmbBln']) ? strval($_REQUEST['cmbBln']) : '';
	$cmbBln_txt = isset($_REQUEST['cmbBln_txt']) ? strval($_REQUEST['cmbBln_txt']) : '';
	$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

	include("../connect/conn.php");

	$sql = "select * from zvw_fg_inventory";

	$data = oci_parse($connect, $sql);
	oci_execute($data);	

	$items = array();
	$rowno=0;
	
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$INVENTORYBULANLALU = $items[$rowno]->INVENTORYBULANLALU;
		$AMOUNTINVENTORYBULANLALU = $items[$rowno]->AMOUNTINVENTORYBULANLALU;
		$INVENTORYBULANINI = $items[$rowno]->INVENTORYBULANINI;
		$AMOUNTINVENTORYBULANINI = $items[$rowno]->AMOUNTINVENTORYBULANINI;

		$items[$rowno]->INVENTORYBULANLALU = number_format($INVENTORYBULANLALU,2);
		$items[$rowno]->AMOUNTINVENTORYBULANLALU = number_format($AMOUNTINVENTORYBULANLALU,2);
		$items[$rowno]->INVENTORYBULANINI = number_format($INVENTORYBULANINI,2);
		$items[$rowno]->AMOUNTINVENTORYBULANINI = number_format($AMOUNTINVENTORYBULANINI,2);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>