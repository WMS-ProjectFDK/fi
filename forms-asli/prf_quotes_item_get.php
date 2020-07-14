<?php
	session_start();
	$result = array();

	$qno = isset($_REQUEST['qno']) ? strval($_REQUEST['qno']) : '';

	include("../connect/conn2.php");

	$rowno=0;
	$rs = "select a.quotation_no, a.item_no, b.description, a.file_1, a.file_2, a.file_3, 0 as qty from ztb_prf_quotation_detail_item a left join item b on a.item_no=b.item_no where quotation_no='$qno'";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$f1= $items[$rowno]->FILE_1;
		$items[$rowno]->FILE_1 = substr($f1, 7);
		$f2= $items[$rowno]->FILE_2;
		$items[$rowno]->FILE_2 = substr($f2, 7);
		$f3= $items[$rowno]->FILE_3;
		$items[$rowno]->FILE_3 = substr($f3, 7);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>