<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct slip_no from mte_header 
		where slip_date >= (select add_months(sysdate,-3) from dual) AND 
		slip_date <= (select sysdate from dual)
		order by slip_no asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"slip_no"=>rtrim($row[0]),
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>