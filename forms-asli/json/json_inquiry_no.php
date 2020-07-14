<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct inq_no from ztb_inquiry_header";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"inq_no"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>