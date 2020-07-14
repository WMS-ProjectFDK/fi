<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct a.item_no, b.description from mps_header a left join item b on a.item_no=b.item_no";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrNo = 0;
	$arrData = array();

	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"item_no"=>rtrim($row[0]), 
			"description"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>