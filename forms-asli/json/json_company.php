<?php
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$idx = isset($_REQUEST['index']) ? strval($_REQUEST['index']) : '';
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select company_code, company from company where company_type in (2,3) and delete_type is null order by company asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"company_code"=>rtrim($row[0]), 
			"company"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>