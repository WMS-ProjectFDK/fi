<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$yearmin = intval(date('Y'))-1;
	$year = date('Y');
	$yearplus = intval(date('Y')+1);
	//echo $yearmin.$year.$yaerplus;
	$sql = "select distinct prf_no from prf_header where year(prf_date) = '$yearmin' OR year(prf_date) = '$year'
		OR year(prf_date) = '$yearplus' order by prf_no asc";
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