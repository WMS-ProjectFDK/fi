<?php
	include("../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select DISTINCT id_rack from ztb_wh_rack where warehouse='WH-FLAMMABLE'";
	$data = oci_parse($connect, $sql);
	oci_execute($data);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($data)){
		$arrData[$arrNo] = array(
			"rack" => rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>