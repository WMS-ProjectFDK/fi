<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$yearmin = intval(date('Y'))-1;
	$year = date('Y');
	$yearplus = intval(date('Y')+1);
	$sql = "select distinct prf_no from prf_header 
		where CAST(prf_date as char(4)) = '$yearmin' 
		OR CAST(prf_date as char(4)) = '$year'
		OR CAST(prf_date as char(4)) = '$yearplus' 
		order by prf_no asc";
	$result = sqlsrv_query($connect, $sql);
	$arrNo = 0;
	$arrData = array();
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"prf_no"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>