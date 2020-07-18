<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select DISTINCT di_no from ztb_di_header";
	$result = sqlsrv_query($connect, strtoupper($sql));
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array("di_number"=>rtrim($row[0]));
		$arrNo++;
	}
	echo json_encode($arrData);
?>