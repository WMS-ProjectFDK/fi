<?php
	include("../../connect/conn.php");
	session_start();
	$result = array();
	$items = array();
	$rowno=0;

	$sch = isset($_REQUEST['sch']) ? strval($_REQUEST['sch']) : '';

	$rs = "select distinct port_code, port from port 
		where upper(port) like upper('%$sch%')
		order by port";
	$data = sqlsrv_query($connect, strtoupper($rs));
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		// $pdy = $items[$rowno]->PDAYS;
		// $pds = $items[$rowno]->PDESC;
		// $items[$rowno]->PAYMENT = $pdy.'-'.$pds;
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>