<?php
	session_start();
	$doc = isset($_REQUEST['doc']) ? strval($_REQUEST['doc']) : '';
	
	include("../connect/conn2.php");

	$sql = "select departement,budget from ztb_prf_parameter where doc_no='$doc'";
	$data = oci_parse($connect, $sql);
	oci_execute($data);
	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$b = $items[$rowno]->BUDGET;
		$items[$rowno]->BUDGET = number_format($b,2);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>