<?php
	include("../connect/conn.php");
	header("Content-type: application/json");
	$q = isset($_POST['q']) ? $_POST['q'] : '';
	
	$sql = "select a.person_code,a.person,a.description from person a order by a.person_code asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_object($result)){
		$arrData[$arrNo] = array(
			"username"=>rtrim($row->PERSON), 
			"userid"=>rtrim($row->PERSON_CODE),
			"desc"=>rtrim($row->DESCRIPTION)
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>