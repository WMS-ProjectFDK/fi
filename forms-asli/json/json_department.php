<?php
	include("../../connect/conn2.php");
	header("Content-type: application/json");
	$sql = "select type, description from person_type order by type asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"id_department"=>rtrim($row[0]), 
			"department"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>