<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct warehouse from ztb_wh_rack order by warehouse asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"wrh"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>