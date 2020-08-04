<?php
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$idx = isset($_REQUEST['index']) ? strval($_REQUEST['index']) : '';
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select company_code, company, cast(company_code as varchar(50))+' - '+company as comb_company from company where company_type=5 order by company asc";
	
	$result = sqlsrv_query($connect, strtoupper($sql));
	
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_object($result)){
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