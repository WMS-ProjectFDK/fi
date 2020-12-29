<?php
	session_start();
	$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

	if ($src !='') {
		$where="where (i.ITEM_NO like '%$src%' OR i.ITEM LIKE '%$src%' OR i.DESCRIPTION LIKE '%$src%')";
		$top = "";
	}else{
		$where=" ";
		$top = "TOP 150";
	}

	include("../../connect/conn.php");

	$sql = "select $top i.item_no, i.item, i.description, i.standard_price
		from item i
		$where
		order by stock_subject_code, description asc";
	$data = sqlsrv_query($connect, strtoupper($sql));
	$items = array();
	$rowno=0;

	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}

	$result["rows"] = $items;
	echo json_encode($result);	
?>