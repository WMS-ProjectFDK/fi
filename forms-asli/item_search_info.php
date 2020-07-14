<?php
	session_start();
	$arrData = array();
	
	$rack = isset($_REQUEST['rack']) ? strval($_REQUEST['rack']) : '';

	include("../connect/conn.php");
	$rowno=0;

	$rs = "select a.rack,a.item_no||' - '||b.description as barang, a.qty-a.qty_out as qty,a.pallet,TO_CHAR(TO_DATE(a.tanggal,'YYYY-MM-DD'), 'YYYY-MM-DD') as tgl, c.warehouse 
		from ztb_wh_in_det a left join item b on a.item_no=b.item_no left join ztb_wh_rack c on a.rack=c.id_rack where a.rack='$rack' and a.qty-a.qty_out > 0 order by a.pallet asc";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while ($row = oci_fetch_object($data)){
		$qty = intval($row->QTY);
		array_push($items, $row);
		$items[$rowno]->QTY = number_format($qty);
		$rowno++;
	}
	/*echo json_encode($arrData);*/
	$result["rows"] = $items;
	echo json_encode($result);
?>