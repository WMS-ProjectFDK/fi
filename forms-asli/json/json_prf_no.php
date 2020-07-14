<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$yearmin = intval(date('Y'))-1;
	$year = date('Y');
	$yearplus = intval(date('Y')+1);
	//echo $yearmin.$year.$yaerplus;
	$sql = "select distinct prf_no from prf_header where TO_CHAR(prf_date,'YYYY') = '$yearmin' OR TO_CHAR(prf_date,'YYYY') = '$year'
		OR TO_CHAR(prf_date,'YYYY') = '$yearplus' order by prf_no asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrNo = 0;
	$arrData = array();
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"prf_no"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>