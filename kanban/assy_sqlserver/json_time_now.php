<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select convert(varchar,GETDATE(),120) as dt";
	$result = sqlsrv_query($connect, $sql);
	$arrNo = 0;
	$arrData = array();
	while ($row=sqlsrv_fetch_object($result)){
		$arrData[$arrNo] = array(
			"kode"=>rtrim($row->dt)
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>