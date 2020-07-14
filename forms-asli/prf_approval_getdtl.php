<?php
	session_start();
	
	$req_no = isset($_REQUEST['req_no']) ? strval($_REQUEST['req_no']) : '';
	$result = array();
	
	include("../connect/conn2.php");
	
	$rs = "select a.req_no, a.id, a.item_no, b.description, b.uom_q, c.unit, a.price, a.qty from ztb_prf_req_details a
		left join item b on a.item_no=b.item_no left join unit c on b.uom_q=c.unit_code
		where a.req_no='$req_no' and a.vendor is null and a.sts_approval='0' and a.sts_PO='0'";
	$data = oci_parse($connect, $rs);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$p = $items[$rowno]->PRICE;
		$items[$rowno]->PRICE = number_format($p);
		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q);
		$t = $p*$q;
		$items[$rowno]->TOTAL = number_format($t);
		$items[$rowno]->STS = '0';
		$rowno++;
	}

	$result["rows"] = $items;

	echo json_encode($items);

?>