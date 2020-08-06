<?php
	include("../../connect/conn.php");
	session_start();
	$result = array();
	$items = array();
	$rowno=0;

	$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';

	$rs = "select ct.contract_seq, ct.curr_code, ct.tterm, ct.pmethod, ct.pdays, ct.pdesc, 
		ct.loading_port, ct.discharge_port, ct.final_dest, ct.port_loading_code, ct.port_discharge_code, ct.final_destination_code
		from contract ct 
		where ct.company_code = '$id'";
	$data = sqlsrv_query($connect, strtoupper($rs));
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$pdy = $items[$rowno]->PDAYS;
		$pds = $items[$rowno]->PDESC;
		$items[$rowno]->PAYMENT = $pdy.'-'.$pds;
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>