<?php
	session_start();
	include("../../connect/conn.php");

	$sql = "select TOP 200 item_no, item, description, standard_price
        from item 
        order by item_no asc";
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