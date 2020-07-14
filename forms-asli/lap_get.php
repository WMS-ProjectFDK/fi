<?php
	session_start();
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_type =  isset($_REQUEST['ck_type']) ? strval($_REQUEST['ck_type']) : '';
	$cmb_type = isset($_REQUEST['cmb_type']) ? strval($_REQUEST['cmb_type']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';


	if ($ck_type != "true"){
		$stat = "STAT ='$cmb_type' and ";
	}else{
		 $stat = "";
	}

	if ($ck_item_no != "true"){
		$item = "item_no='$cmb_item_no' and ";
	}else{
		$item = "";
	}

	$where ="where $stat $item item_no is not null";
	
	include("../connect/conn.php");

	$sql = "select distinct item_no, item_name, label_type, work_order,grade, packaging_type from zvw_finishing
		where item_no=11450 order by item_no asc";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>