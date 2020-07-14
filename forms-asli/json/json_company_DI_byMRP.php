<?php
	header("Content-type: application/json");
	include("../../connect/conn.php");
	
	$sql = "select company_code, company, company||' ['||company_code||']' as comb_company, attn 
		from company where company_type=3 order by company asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_object($result)){
		array_push($arrData, $row);
	}
	echo json_encode($arrData);
?>