<?php
	include("../../connect/conn.php");
	session_start();
	$result = array();
	$items = array();
	$rowno=0;

	$rs = "select CUSTOMER_PO_NO, SO_NO, CAST(SO_DATE as varchar(10)) as SO_DATE, CONSIGNEE_FROM_JP, REMARK
		from SO_HEADER
		where 1 = 1
		order by upper(CUSTOMER_PO_NO)";
	$data = sqlsrv_query($connect, strtoupper($rs));
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>