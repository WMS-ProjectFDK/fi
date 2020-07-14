<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$sql = "select max(del_date) DEL_DATE from ztb_mrp_data_pck_delete";
	
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"DEL_DATE"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>