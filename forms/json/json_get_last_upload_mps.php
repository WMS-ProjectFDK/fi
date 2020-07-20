<?php
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$idx = isset($_REQUEST['index']) ? strval($_REQUEST['index']) : '';
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select
		(select distinct top 1 CAST(upload_date as varchar(20)) as LASTED from MPS_HEADER) as LISTED,
		(select distinct top 1 CAST(upload_date as varchar(20)) as listed from mps_header_rireki 
					where upload_date > (select CAST(DATEADD(day,-100,getdate()) as date)) 
					and CAST(upload_date as varchar(10)) <> (select distinct top 1 CAST(upload_date as varchar(10)) LISTED from mps_header)
		) as LASTED";
	$result = sqlsrv_query($connect, strtoupper($sql));
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"LISTED"=>rtrim($row[0]), 
			"LASTED"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>