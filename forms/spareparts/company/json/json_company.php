<?php
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$idx = isset($_REQUEST['index']) ? strval($_REQUEST['index']) : '';
	include("../../../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select company_code, company from sp_company where  delete_type is null order by company asc";
	$result = sqlsrv_query($connect, $sql);
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"company_code"=>rtrim($row[0]), 
			"company"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>