<?php
	session_start();
	$result = array();

	$vendor = isset($_REQUEST['vendor']) ? strval($_REQUEST['vendor']) : '';

	include("../connect/conn2.php");

	$rowno=0;
	$rs = "select a.id,a.req_no,a.item_no,b.description,a.unit_code,c.unit,a.price,a.qty,a.price*a.qty as total, d.type_budget from ztb_prf_req_details a
		left join item b on a.item_no=b.item_no left join unit c on a.unit_code=c.unit_code left join ztb_prf_req_header d on a.req_no=d.req_no
		where a.vendor='$vendor' and a.sts_approval='1' and a.sts_po='0' order by a.id asc";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q);
		$p = $items[$rowno]->PRICE;
		$items[$rowno]->PRICE = number_format($p);
		$t = $items[$rowno]->TOTAL;
		$items[$rowno]->TOTAL = number_format($t);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>