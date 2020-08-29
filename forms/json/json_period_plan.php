<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$sql = "SELECT 
        LEFT(CONVERT(varchar, GetDate(),112),6) as this_month,
        UPPER(CONCAT(
            CONVERT(CHAR(3), CONVERT(DATE, getdate(), 105), 0), 
            '-',
            DATEPART(YYYY, CONVERT(DATE, getdate(), 105))
            )) as this_month_text,
        LEFT(CONVERT(varchar, DATEADD(month,1,getdate()),112),6) as next_month,
        UPPER(CONCAT(
            CONVERT(CHAR(3), CONVERT(DATE, DATEADD(month,1,getdate()), 105), 0), 
            '-',
            DATEPART(YYYY, CONVERT(DATE, DATEADD(month,1,getdate()), 105))
            )) as next_month_text,
        LEFT(CONVERT(varchar, DATEADD(month,2,getdate()),112),6) as next_2month,
        UPPER(CONCAT(
            CONVERT(CHAR(3), CONVERT(DATE, DATEADD(month,2,getdate()), 105), 0), 
            '-',
            DATEPART(YYYY, CONVERT(DATE, DATEADD(month,2,getdate()), 105))
            )) as next_2month_text";

	$data = sqlsrv_query($connect, strtoupper($sql));

	while($dt_result = sqlsrv_fetch_object($data)){
		$arrData[] = array("bln"=>$dt_result->THIS_MONTH, "blnA"=>$dt_result->THIS_MONTH_TEXT, "selected"=>true);
		$arrData[] = array("bln"=>$dt_result->NEXT_MONTH, "blnA"=>$dt_result->NEXT_MONTH_TEXT);
		$arrData[] = array("bln"=>$dt_result->NEXT_2MONTH, "blnA"=>$dt_result->NEXT_2MONTH_TEXT);
	}

	echo json_encode($arrData);
?>