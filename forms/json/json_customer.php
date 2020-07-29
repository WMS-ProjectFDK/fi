<?php
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$idx = isset($_REQUEST['index']) ? strval($_REQUEST['index']) : '';
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select c.company_code, c.company from company c 
		where c.country_code != 926168 and c.delete_type is null 
		order by c.company";
	$result = sqlsrv_query($connect, strtoupper($sql));
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"company_code"=>rtrim($row[0]), 
			"company"=>strtoupper(rtrim($row[1])).' ['.rtrim($row[0]).']'
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>