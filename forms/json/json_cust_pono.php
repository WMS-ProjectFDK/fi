<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$yearmin = intval(date('Y'))-1;
	$year = date('Y');
	$yearplus = intval(date('Y')+1);
	//echo $yearmin.$year.$yaerplus;
	$sql = "select customer_po_no from so_header 
        where CAST(so_date as char(4)) between '$yearmin' and '$yearplus' 
        order by so_date desc, so_no asc";
	$result = sqlsrv_query($connect, $sql);
	$arrNo = 0;
    $arrData = array();
    // echo $sql;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"cust_po_no"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>