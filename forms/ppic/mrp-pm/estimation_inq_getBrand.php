<?php
	session_start();
	$result = array();
	$items = array();

	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';

	if($item!=''){
		$where = "where a.item_no like '%".strtoupper($item)."%' or a.item like '%".strtoupper($item)."%' or a.description like '%".strtoupper($item)."%' and a.item_flag=1 and a.stock_subject_code = 5";
	}else{
		$where = "where a.item_flag=1 and a.stock_subject_code = 5";
	}

	include("../../../connect/conn.php");
	$rowno=0;

	$rs = "select a.item_no, a.item, a.description, b.unit, c.lvl
		from item a
		inner join unit b on a.uom_q = b.unit_code
		inner join (select upper_item_no, max(level_no) as lvl from structure group by upper_item_no) c on a.item_no = c. upper_item_no
		$where
		order by a.item_no asc";
	$data = sqlsrv_query($connect, strtoupper($rs));
	
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$rowno++;
	}

	$result["rows"] = $items;
	echo json_encode($result);
?>