<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct slip_no from production_income 
		where slip_date > DATEADD(MONTH, -2, GETDATE())
		order by slip_no asc";
	$result = sqlsrv_query($connect, $sql);
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"slip_no"=>rtrim($row[0]),
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>