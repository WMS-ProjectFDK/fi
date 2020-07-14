<?php
	include("../../connect/conn_kanbansys.php");
	header("Content-type: application/json");
	$sql = "select distinct cast(revisi as int) as revisi from ztb_assy_plan order by revisi";
	$result = odbc_exec($connect, $sql);
	$arrData = array();
	$arrNo = 1;
	$arrData[0] = array("revisi"=> "USED", "selected" => true);
	while ($row=odbc_fetch_object($result)){
		$arrData[$arrNo] = array(
			"revisi"=>rtrim($row->revisi)
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>