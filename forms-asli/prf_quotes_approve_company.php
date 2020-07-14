<?php
	session_start();
	$qtt = isset($_REQUEST['quotation']) ? strval($_REQUEST['quotation']) : '';
	
	include("../connect/conn2.php");

	$sql = "select a.*, b.description from ztb_prf_quotation_detail_item a left join item b on a.item_no=b.item_no where a.quotation_no='$qtt' order by b.description asc";
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