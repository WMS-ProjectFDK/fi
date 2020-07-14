<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$arrData = array();
	$arrNo = 0;

	$sql = "select  distinct c.company_code, c.company
		from company c
		where c.company_type in(1,2)
		order by c.company";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"company_code"=>rtrim($row[0]), 
			"company"=>strtoupper(rtrim($row[1])).' ['.rtrim($row[0]).']'
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>