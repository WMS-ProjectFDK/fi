<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';

	$sql = "select distinct upper_item_no,level_no from structure where upper_item_no = '$item'";
	
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrNo = 0;
	$arrData = array();
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"upper_item_no"=>rtrim($row[0]),
			"level_no"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>