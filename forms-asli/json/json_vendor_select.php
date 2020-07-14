<?php
	$vendor = isset($_REQUEST['vendor']) ? strval($_REQUEST['vendor']) : '';
	
	include("../../connect/conn2.php");
	header("Content-type: application/json");
	$sql = "select pdays||' '||pdesc as terms from company where company_code='$vendor'";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"TERMS"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>