<?php
	include("../../connect/conn.php");
	session_start();
	$result = array();
	$items = array();
	$rowno=0;

	$rs = "select FORWARDER_NO, NAME, ADDR1 + ADDR2 + ADDR3 as ADDR, ADDR1, ADDR2, ADDR3, TEL, FAX, ATTN
        from SI_FORWARDER
        where DELETE_TYPE is null
        order by upper(NAME)";
	$data = sqlsrv_query($connect, strtoupper($rs));
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>