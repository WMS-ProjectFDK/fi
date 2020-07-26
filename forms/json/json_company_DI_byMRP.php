<?php
	header("Content-type: application/json");
	include("../../connect/conn.php");
	
	$sql = "select company_code, company, company+' ['+cast(company_code as varchar)+']' as comb_company, attn 
        from company where company_type=3 order by company asc";
	$result = sqlsrv_query($connect, strtoupper($sql));
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_object($result)){
		array_push($arrData, $row);
	}
	echo json_encode($arrData);
?>