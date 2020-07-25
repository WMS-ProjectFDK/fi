<?php
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$idx = isset($_REQUEST['index']) ? strval($_REQUEST['index']) : '';
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct CAST(upload_date as varchar(20)) as LASTED, UPLOAD_DATE from MPS_HEADER_RIREKI
		where UPLOAD_DATE > (select CAST(DATEADD(day,-100,getdate()) as date))
		and CAST(upload_date as varchar(10)) <> (select distinct CAST(upload_date as varchar(10)) from mps_header)
		order by upload_date desc";
	$result = sqlsrv_query($connect, strtoupper($sql));
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"LASTED"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>