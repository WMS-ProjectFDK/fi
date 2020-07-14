<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select type, description from person_type order by type asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"ID_DEPT"=>rtrim($row[0]), 
			"DEPARTEMENT"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>