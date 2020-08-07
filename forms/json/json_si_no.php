<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$user = isset($_REQUEST['user']) ? strval($_REQUEST['user']) : '';
    $sql = "select si_no, cast(si_no as varchar(20))||'-'||cust_si_no cust from si_header 
        where cust_si_no is not null 
        order by si_no desc";
	$result = sqlsrv_query($connect, strtoupper($sql));
	$arrNo = 0;
	$arrData = array();
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"si_no"=>rtrim($row[0]),
			"cust"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>