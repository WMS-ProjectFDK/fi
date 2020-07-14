<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$sql = "select 
		to_char(trunc(sysdate),'YYYYMM') as this_month,
		upper(to_char (trunc(sysdate),'MON/YYYY')) as this_month_text,
		to_char(add_months(trunc(sysdate,'MM'), 1),'YYYYMM') as next_month,
		upper(to_char(add_months(trunc(sysdate,'MM') ,1),'MON/YYYY')) as next_month_text,
    	to_char(add_months(trunc(sysdate,'MM'), 2),'YYYYMM') as next_2month,
		upper(to_char(add_months(trunc(sysdate,'MM') ,2),'MON/YYYY')) as next_2month_text
		from dual";

	$data = oci_parse($connect, $sql);
	oci_execute($data);

	while($dt_result = oci_fetch_object($data)){
		$arrData[] = array("bln"=>$dt_result->THIS_MONTH, "blnA"=>$dt_result->THIS_MONTH_TEXT, "selected"=>true);
		$arrData[] = array("bln"=>$dt_result->NEXT_MONTH, "blnA"=>$dt_result->NEXT_MONTH_TEXT);
		$arrData[] = array("bln"=>$dt_result->NEXT_2MONTH, "blnA"=>$dt_result->NEXT_2MONTH_TEXT);
	}

	echo json_encode($arrData);
?>