<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct slip_no from production_income where slip_date > add_months(SYSDATE, -2) order by slip_no asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"slip_no"=>rtrim($row[0]),
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>