<?php
	include("../../../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct slip_no from sp_mte_header 
	where slip_date >= cast( getdate() - 90 as date) AND 
	slip_date <= cast(getdate() as date) and slip_no is not null
	order by slip_no asc";
	$result = sqlsrv_query($connect, $sql);
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"slip_no"=>rtrim($row[0]),
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>