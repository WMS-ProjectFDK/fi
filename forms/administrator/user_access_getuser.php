<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$q = isset($_POST['q']) ? $_POST['q'] : '';
	
	$sql = "select a.person_code,a.person,a.description from person a order by a.person_code asc";
	$result = sqlsrv_query($connect, $sql);
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_object($result)){
		$arrData[$arrNo] = array(
			"username"=>rtrim($row->person), 
			"userid"=>rtrim($row->person_code),
			"desc"=>rtrim($row->description)
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>