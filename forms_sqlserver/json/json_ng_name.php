<?php
	$ng_pro = isset($_REQUEST['ng_pro']) ? strval($_REQUEST['ng_pro']) : '';
	include("../../connect/conn_kanbansys.php");
	header("Content-type: application/json");

	$sql = "select ng_id, ng_name from ztb_assy_ng where ng_id_proses=$ng_pro order by ng_id asc";
	$result = odbc_exec($connect, $sql);
	$arrData = array();
	$arrNo = 0;
	while ($row=odbc_fetch_object($result)){
		$arrData[$arrNo] = array(
			"ng_id"=>rtrim($row->ng_id),
			"ng_name"=>rtrim($row->ng_name)
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>