<?php
	include("../../connect/conn.php");
	session_start();
	$result = array();
	$items = array();
	$rowno=0;

	$rs = "select PORT_CODE as CODE, NAME from SI_PORT
        where DELETE_TYPE is null  and PORT_CODE is not null
        order by upper(PORT_CODE)";
	$data = sqlsrv_query($connect, strtoupper($rs));
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>