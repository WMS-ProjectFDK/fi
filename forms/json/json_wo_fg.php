<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct wo_no from production_income 
        where CAST(CONVERT(date, slip_date,102) as varchar(4)) >= '2018' 
        and wo_no is not null";
	$result = sqlsrv_query($connect, $sql);
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"wo_no"=>rtrim($row[0]),
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>