<?php
	session_start();
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$quotation = isset($_REQUEST['quotation']) ? strval($_REQUEST['quotation']) : '';
	
	include("../connect/conn2.php");

	$sql = "select a.vendor, b.company, a.price, a.flag_approved from ztb_prf_quotation_detail_comp a left join company b on a.vendor = b.company_code
	 where a.quotation_no='$quotation' and a.item_no='$item' ";
	$data = oci_parse($connect, $sql);
	oci_execute($data);
	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$pric = $items[$rowno]->PRICE;
		$items[$rowno]->PRICE = number_format($pric);
		$f= $items[$rowno]->FLAG_APPROVED;
		if($f=='1'){
			$items[$rowno]->FLAG_APPROVED = '<span style="color:blue;font-size:11px;"><b>APPROVED</b></span>';
		}else{
			$items[$rowno]->FLAG_APPROVED = '<span style="color:red;font-size:11px;"><b>NOT APPROVED</b></span>';
		}
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>