<?php
	include("../connect/conn.php");
	ini_set('max_execution_time', -1);
	session_start();
	$items = array();
	$rowno=0;

	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	
	$cek = "select item_no, package_type, groups_pck, man_power, capacity from ztb_item_pck where item_no ='$item_no'" ;
	$data_cek = oci_parse($connect, $cek);
	oci_execute($data_cek);

	while($row = oci_fetch_object($data_cek)){
		array_push($items, $row);
		$rowno++;
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
?>