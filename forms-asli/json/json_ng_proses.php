<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct ng_id_proses, ng_name_proses from ztb_assy_ng order by ng_id_proses asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"ng_proses_id"=>rtrim($row[0]),
			"ng_proses_name"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>