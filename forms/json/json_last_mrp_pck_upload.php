<?php
	date_default_timezone_set('Asia/Jakarta');
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$sql = "select max(del_date) DEL_DATE from ztb_mrp_data_pck_delete";
	
	$result = sqlsrv_query($connect, $sql);
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"DEL_DATE"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>