<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$sql = "select distinct
		max(this_month) as this_month,RIGHT(THIS_MONTH,2) + '-' + left(THIS_MONTH,4) as this_month_text,
		max(last_month) as last_month,RIGHT(last_month,2) + '-' + left(last_month,4) as last_month_text
		from whinventory  group by THIS_MONTH,LAST_MONTH";

	$data = sqlsrv_query($connect, strtoupper($sql));


	while($dt_result = sqlsrv_fetch_object($data)){
		$arrData[] = array("bln"=>$dt_result->THIS_MONTH, "blnA"=>$dt_result->THIS_MONTH_TEXT, "selected"=>true);
		$arrData[] = array("bln"=>$dt_result->LAST_MONTH, "blnA"=>$dt_result->LAST_MONTH_TEXT);
	}

	echo json_encode($arrData);
?>