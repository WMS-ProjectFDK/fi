<?php
	$ng_pro = isset($_REQUEST['ng_pro']) ? strval($_REQUEST['ng_pro']) : '';
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$sql = "select ng_id, ng_name from ztb_assy_ng where ng_id_proses=$ng_pro order by ng_id asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"ng_id"=>rtrim($row[0]),
			"ng_name"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>