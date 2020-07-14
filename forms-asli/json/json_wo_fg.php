<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct wo_no from production_income where to_char(slip_date,'YYYY') >= '2018' 
		and wo_no is not null";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"wo_no"=>rtrim($row[0]),
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>