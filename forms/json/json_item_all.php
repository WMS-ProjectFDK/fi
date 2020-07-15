<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct item_no,description from item order by description asc";
	$result = sqlsrv_query($connect, $sql);
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"id_item"=>rtrim($row[0]), 
			"name_item"=>rtrim($row[1]),
			"id_name_item"=>rtrim($row[0])." - ".rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>