<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select com.company_code, com.company from company com 
		where com.company_type in (0,7)
		order by com.company_type, com.company";
	$result = sqlsrv_query($connect, strtoupper($sql));
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"com_code"=>rtrim($row[0]), 
			"com_name"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>