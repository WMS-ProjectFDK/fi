<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$sql = "select class_code,class_1+'-'+CLASS_2 as class from class order by class_code asc";
	$result = sqlsrv_query($connect, $sql);
	$arrNo = 0;
	$arrData = array();
	while ($row=sqlsrv_fetch_object($result)){
		array_push($arrData, $row);
		$arrNo++;
	}
	echo json_encode($arrData);
?>