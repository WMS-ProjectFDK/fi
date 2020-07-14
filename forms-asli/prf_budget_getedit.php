<?php
	session_start();
	
	$doc_no = isset($_REQUEST['doc_no']) ? strval($_REQUEST['doc_no']) : '';
	$result = array();
	
	include("../connect/conn2.php");
	
	$rs = "select id_dept, departement,budget from ztb_prf_parameter where doc_no='$doc_no'";
	$data = oci_parse($connect, $rs);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$b = $items[$rowno]->BUDGET;
		$items[$rowno]->BUDGET = number_format($b);
		$rowno++;
	}

	$result["rows"] = $items;
	echo json_encode($items);

?>