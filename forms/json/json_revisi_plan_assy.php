<?php
    include("../../connect/conn.php");
    header("Content-type: application/json");
    
	$sql = "select distinct cast(revisi as int) as revisi from ztb_assy_plan order by revisi";
	$result = sqlsrv_query($connect, $sql);
	$arrData = array();
	$arrNo = 1;
	$arrData[0] = array("revisi"=> "USED", "selected" => true);
	while ($row=sqlsrv_fetch_object($result)){
		$arrData[$arrNo] = array(
			"revisi"=>rtrim($row->revisi)
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>