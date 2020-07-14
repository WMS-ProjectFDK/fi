<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$item = htmlspecialchars($_REQUEST['item']);
	$sql = "select distinct item_no,description from item 
			where item_no = $item
			order by description asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"id_item"=>rtrim($row[0]), 
			"name_item"=>rtrim($row[1]),
			"id_name_item"=>rtrim($row[0])." - ".rtrim($row[1]),
			"selected"=>true
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>