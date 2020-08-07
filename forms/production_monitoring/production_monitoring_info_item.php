<?php
	ini_set('max_execution_time', -1);
	session_start();
	$items = array();
	$rowno=0;
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
    include("../../connect/conn.php");
    
	$cek = "select item_no, package_type, groups_pck, man_power, capacity from ztb_item_pck where item_no ='$item_no'" ;
	$data_cek = sqlsrv_query($connect, strtoupper($cek));

	while($row = sqlsrv_fetch_object($data_cek)){
		array_push($items, $row);
		$rowno++;
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
?>