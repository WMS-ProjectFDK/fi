<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct revisi from ztb_assy_plan order by revisi";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 1;
	$arrData[0] = array("revisi"=> "USED", "selected" => true);
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"revisi"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>