<?php
	session_start();
	include("../../connect/conn.php");

	$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

	if ($src !='') {
		$sql = "select distinct a.*, c.description, b.asin, b.amazon_po_no
			from ztb_amazon_wo a
			inner join ztb_amazon_wo_details b on a.wo= b.wo
			inner join item c on a.item=c.item_no
			where b.asin like '%$src%' OR b.amazon_po_no like '%$src%'";
	}
	$data = sqlsrv_query($connect, strtoupper($sql));
    
    $items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$t = $items[$rowno]->TOTAL_CARTON;
		$s = $items[$rowno]->START_CARTON;
		$q = $items[$rowno]->QUANTITY;

		$items[$rowno]->TOTAL_CARTON = number_format($t);
		$items[$rowno]->START_CARTON = number_format($s);
		$items[$rowno]->QUANTITY = number_format($q);

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>