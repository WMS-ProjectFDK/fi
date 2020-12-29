<?php
	$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';
	include("../../../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct item_no,replace(replace(description,'&',''),'# ','') description from sp_item 
		where delete_type is null and item_no + description like '%$id%'
		order by description asc";
	
	$result = sqlsrv_query($connect, $sql);
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"id_item"=>rtrim($row[0]), 
			"name_item"=>rtrim($row[1]),
			"id_name_item"=>rtrim($row[0])." - ".rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>