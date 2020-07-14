<?php
	include("../connect/conn.php");
	session_start();
	$result = array();
	$nik = isset($_REQUEST['nik']) ? strval($_REQUEST['nik']) : '';
	
	$sql = "select a.id, a.menu_parent, a.menu_sub_parent, a.menu_name from ztb_menu a order by a.id";
	$data = oci_parse($connect, $sql);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>