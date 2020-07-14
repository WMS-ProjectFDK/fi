<?php
	include("../../connect/conn2.php");
	header("Content-type: application/json");
	$sql = "select curr_code, curr_short from currency order by curr_code asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"idcrc"=>rtrim($row[0]), 
			"nmcrc"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>