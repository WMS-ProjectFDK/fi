<?php
	include("../../../../connect/conn.php");
	header("Content-type: application/json");
	$yearmin = intval(date('Y'))-1;
	$year = date('Y');
	$yearplus = intval(date('Y')+1);
	//echo $yearmin.$year.$yaerplus;
	$sql = "select top 200 gr_no, gr_date from sp_gr_header 
		where year(gr_date) between 2019 and 2025 
		order by gr_date desc, gr_no asc";
	//echo $sql;
	$result = sqlsrv_query($connect, strtoupper($sql));
	$arrNo = 0;
	$arrData = array();
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"gr_no"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>