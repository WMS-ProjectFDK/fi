<?php
	include("../../connect/conn2.php");
	header("Content-type: application/json");
	$sql = "select unit_code,unit from unit order by unit_code asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"idunit"=>rtrim($row[0]), 
			"nmunit"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>