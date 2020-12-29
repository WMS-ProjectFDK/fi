<?php
	// error_reporting(0);
	session_start();
	$result = array();
	$bypil = '';
	$by = isset($_REQUEST['by']) ? strval($_REQUEST['by']) : ''; 
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';

	if($by == "ITEM_NO"){
		$bypil = " a.item_no like '%".strtoupper($item)."%' ";
	}elseif($by == "DESCRIPTION"){
		$bypil = " b.description like '%".strtoupper($item)."%' ";
	}

	include("../../../connect/conn.php");
	$rowno=0;

	$rs = "select a.item_no, b.description, b.uom_q, c.unit, a.this_inventory, 0 as no_approve,
		a.this_inventory as stock,
		0 as slip_qty, '-' as wo_no, '-' as date_code, '-' as remark 
		from SP_WHINVENTORY a
		inner join sp_item b on a.item_no=b.item_no
		inner join sp_unit  c on b.uom_q= c.unit_code
		where ".$bypil." order by b.description asc";
	// echo $rs;
	$data = sqlsrv_query($connect, strtoupper($rs));
	
	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$sto = $items[$rowno]->STOCK;
		$items[$rowno]->STOCK = number_format($sto);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>