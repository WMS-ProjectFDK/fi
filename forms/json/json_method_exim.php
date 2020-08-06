<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$sql = "select method_type, description from cargo_method order by method_type";
	$result = sqlsrv_query($connect, strtoupper($sql));
	$arrNo = 0;
	$arrData = array();
	while ($row=sqlsrv_fetch_object($result)){
		array_push($arrData, $row);
		$arrNo++;
	}
	echo json_encode($arrData);
?>