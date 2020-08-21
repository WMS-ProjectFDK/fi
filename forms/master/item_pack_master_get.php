<?php
	session_start();
	include("../../connect/conn.php");

	$sql = "select a.item_no, i.item, i.description, a.operation_time, a.man_power, a.capacity,
		a.package_type, a.groups_pck, a.second_process, a.second_machine
		from ztb_item_pck a
		inner join item i on a.item_no = i.item_no
		order by description asc";
	$data = sqlsrv_query($connect, strtoupper($sql));
	$items = array();
	$rowno=0;

	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$c = $items[$rowno]->CAPACITY;
		$items[$rowno]->CAPACITY = number_format($c);
		$rowno++;
	}

	$result["rows"] = $items;
	echo json_encode($result);	
?>