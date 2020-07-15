<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select curr_code, curr_short from currency order by curr_code asc";
	$result = sqlsrv_query($connect, $sql);
	
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"idcrc"=>rtrim($row[0]), 
			"nmcrc"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>