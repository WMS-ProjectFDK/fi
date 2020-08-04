<?php
	include("../../connect/conn.php");
	session_start();
	$result = array();
	$rowno=0;

	$rs = "select c.COMPANY_CODE as consignee_code, c.COMPANY as consignee_name
		from COMPANY c
		where c.COMPANY_TYPE = 7
		order by c.COMPANY";
	$data = sqlsrv_query($connect, strtoupper($rs));
	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>