<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select DISTINCT di_no from ztb_di_header";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array("di_number"=>rtrim($row[0]));
		$arrNo++;
	}
	echo json_encode($arrData);
?>