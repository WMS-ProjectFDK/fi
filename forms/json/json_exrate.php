<?php
	$curr = isset($_REQUEST['curr']) ? strval($_REQUEST['curr']) : '';
	
	include("../../connect/conn2.php");
	header("Content-type: application/json");
	$sql = "select rate from ex_rate where curr_code='$curr' and ex_date=(select max(ex_date) from ex_rate where curr_code='$curr') order by ex_date desc";
	$result = sqlsrv($connect, $sql);
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"RATE"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>