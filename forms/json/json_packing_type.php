<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct packing_type_comment from packing_type
		order by packing_type_comment asc";
	$result = sqlsrv_query($connect, strtoupper($sql));
	$arrData = array();
    $arrNo = 0;
    
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"TYPE1"=>rtrim($row[0]),
			"TYPE2"=>"'".rtrim($row[0])."'"
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>