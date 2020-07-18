<?php
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$idx = isset($_REQUEST['index']) ? strval($_REQUEST['index']) : '';
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select company_code, company, company_code||' - '||company as comb_company from company where company_type=3 order by company asc";
	$result = sqlsrv_query($connect, strtoupper($sql));
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_object($result)){
		if($row->COMPANY_CODE=='400094'){
			$row->selected = true;
		}
		array_push($arrData, $row);
	}
	echo json_encode($arrData);
?>