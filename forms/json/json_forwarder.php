<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$sql = "select distinct forwarder_code, forwarder from forwarder 
		where delete_type is null order by forwarder";
	$result = sqlsrv_query($connect, strtoupper($sql));
	$arrNo = 0;
	$arrData = array();
	while ($row=sqlsrv_fetch_object($result)){
		array_push($arrData, $row);
		$arrNo++;
	}
	echo json_encode($arrData);
?>