<?php
	session_start();
	$result = array();
	$items = array();

	$by = isset($_REQUEST['by']) ? strval($_REQUEST['by']) : ''; 
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';

	if($item!=''){
		$where = "where mps.item_no like '%".strtoupper($item)."%' or mps.item_name like '%".strtoupper($item)."%' or mps.work_order like '%".strtoupper($item)."%' ";
	}else{
		$where = "where mps.item_no is null";
	}

	include("../../connect/conn.php");
	$rowno=0;

	$rs = "select mps.item_no, REPLACE(mps.item_name,'@', ' ') as item_name, i.cost_process_code, i.item_type2,
		mps.bom_level, u.unit, mps.work_order, mps.date_code
		from mps_header mps
		inner join item i on mps.item_no = i.item_no
		inner join unit u on i.uom_q= u.unit_code
		$where 
		order by description";
	$data = sqlsrv_query($connect, strtoupper($rs));
	
	
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$rowno++;
	}

	$result["rows"] = $items;
	echo json_encode($result);
?>