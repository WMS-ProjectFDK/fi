<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct ng_id_proses, ng_name_proses from ztb_assy_ng order by ng_id_proses asc";
	$result = sqlsrv_query($connect, $sql);
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_object($result)){
		$arrData[$arrNo] = array(
			"ng_proses_id"=>rtrim($row->ng_id_proses),
			"ng_proses_name"=>rtrim($row->ng_name_proses)
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>