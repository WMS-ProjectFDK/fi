<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select type, description from person_type order by type asc";
	$result = sqlsrv_query($connect, $sql);
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_object($result)){
		$arrData[$arrNo] = array(
			"id_department"=>rtrim($row->type), 
			"department"=>rtrim($row->description)
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>