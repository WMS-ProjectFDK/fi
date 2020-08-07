<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct do_no, do_date from do_header
		where to_char(do_date,'YYYY') between to_char((SELECT add_months(sysdate, -36) FROM DUAL),'YYYY') and to_char(sysdate,'YYYY')
		order by do_date asc";
	$result = sqlsrv_query($connect, strtoupper($sql));
	$arrNo = 0;
	$arrData = array();
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"do_no"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>