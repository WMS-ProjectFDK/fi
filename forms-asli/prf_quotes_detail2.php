<?php
	session_start();
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$quotno = isset($_REQUEST['quotno']) ? strval($_REQUEST['quotno']) : '';
	$desc = isset($_REQUEST['desc']) ? strval($_REQUEST['desc']) : '';/*
	$search = isset($_REQUEST['search']) ? strval($_REQUEST['search']) : '';
	$SEARCH  = strtoupper($search);*/
	
	include("../connect/conn2.php");

	$sql = "select a.item_no, a.supplier_code, b.company, a.estimate_price, a.curr_code, c.curr_short from itemmaker a
		left join company b on a.supplier_code=b.company_code left join currency c on a.curr_code=c.curr_code
		where a.item_no='$item' order by b.company asc";
	$data = oci_parse($connect, $sql);
	oci_execute($data);
	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$items[$rowno]->QUOTATION_NO = $quotno;
		$items[$rowno]->DESCRIPTION = $desc;
		$p = $items[$rowno]->ESTIMATE_PRICE;
		$items[$rowno]->ESTIMATE_PRICE = number_format($p);

		$rowno++;
	}
	$result["rows"] = $items;
	$result["total"] = intval($rowno);
	
	echo json_encode($result);
?>