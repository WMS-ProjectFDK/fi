<?php
	session_start();
	$req = isset($_REQUEST['req']) ? strval($_REQUEST['req']) : '';
	
	include("../connect/conn2.php");

	$sql = "select a.id, a.item_no, b.description, b.uom_q, c.unit, a.price, a.qty from ztb_prf_req_details a
		left join item b on a.item_no=b.item_no left join unit c on b.uom_q = c.unit_code
		where a.req_no='$req' and vendor is null and sts_approval='0' and sts_po='0' order by a.id asc";
	$data = oci_parse($connect, $sql);
	oci_execute($data);
	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$p = $items[$rowno]->PRICE;
		$items[$rowno]->PRICE = number_format($p);
		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q);
		$items[$rowno]->TOTAL = number_format($p*$q);

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>