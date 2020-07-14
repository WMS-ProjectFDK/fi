<?php
	$usr = isset($_REQUEST['usr']) ? strval($_REQUEST['usr']) : '';
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$arrData = array();
	$arrNo = 0;

	$sql = "select * from ztb_worker where worker_id=$usr";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	
	while ($row=oci_fetch_object($result)){
		array_push($arrData, $row);
		$arrNo++;
	}
	echo json_encode($arrData);
?>