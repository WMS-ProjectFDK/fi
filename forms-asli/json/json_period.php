<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$sql = "select distinct
		max(this_month) as this_month,upper(to_char(max(to_date(this_month,'yyyymm')),'mon-yyyy')) as this_month_text,
		max(last_month) as last_month,upper(to_char(max(to_date(last_month,'yyyymm')),'mon-yyyy')) as last_month_text
		from whinventory";

	$data = oci_parse($connect, $sql);
	oci_execute($data);

	while($dt_result = oci_fetch_object($data)){
		$arrData[] = array("bln"=>$dt_result->THIS_MONTH, "blnA"=>$dt_result->THIS_MONTH_TEXT, "selected"=>true);
		$arrData[] = array("bln"=>$dt_result->LAST_MONTH, "blnA"=>$dt_result->LAST_MONTH_TEXT);
	}

	echo json_encode($arrData);
?>