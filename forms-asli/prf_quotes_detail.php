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
		
		$f1= $items[$rowno]->FILE_1;
		$items[$rowno]->FILE_1 = substr($f1, 7);
		//$items[$rowno]->FILE_11 = $f1;
		
		$f2= $items[$rowno]->FILE_2;
		$items[$rowno]->FILE_2 = substr($f2, 7);
		//$items[$rowno]->FILE_22 = $f2;
		
		$f3= $items[$rowno]->FILE_3;
		$items[$rowno]->FILE_3 = substr($f3, 7);
		//$items[$rowno]->FILE_33 = $f3;
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>