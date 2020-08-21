<?php
	session_start();
	include("../../connect/conn.php");

	$sql = "select a.item_no, b.description, a.period, a.drawing_no, CAST(a.upto_date as varchar) as upto_date, a.remark,
		case when a.inkjet_code = 1 then 'SINGLE' else 'DOUBLE' end as inkjet
		from ztb_item_label_inkjet_code a
		inner join item b on a.item_no=b.item_no
		order by a.item_no asc";
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