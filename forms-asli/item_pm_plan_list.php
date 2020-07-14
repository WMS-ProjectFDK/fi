<?php
	session_start();
	include("../connect/conn.php");

	$sql = "select * from zvw_pm_plan where item1 in (select item_no from ztb_safety_stock where period = 8 and year = 2018)";
	$data_sql = oci_parse($connect, $sql);
	oci_execute($data_sql);
	

	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
	array_push($items, $row);

	$i = $items[$rowno]->ITEM1;
	$kode = "select description from item where item_no = '$i'";
	$data_kd = oci_parse($connect, $kode);
	oci_execute($data_kd);
	$dt_kode = oci_fetch_object($data_kd);		
	$DESCRITPION = $dt_kode->DESCRIPTION;
	$items[$rowno]->DESCRIPTION = $DESCRITPION;

	$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);	
	
?>