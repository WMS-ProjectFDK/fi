<?php
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$idx = isset($_REQUEST['index']) ? strval($_REQUEST['index']) : '';
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select company_code, company, company_code||' - '||company as comb_company from company where company_type=5 order by company asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_object($result)){
		if($row->COMPANY_CODE=='100001'){
			$row->selected = true;
		}
		array_push($arrData, $row);
		/*
		$arrData[$arrNo] = array(
			"company_code"=>rtrim($row[0]), 
			"company"=>rtrim($row[1]),
			"comb_company"=>rtrim($row[0])." - ".rtrim($row[1])
		);*/
		//$arrNo++;
	}
	echo json_encode($arrData);
?>