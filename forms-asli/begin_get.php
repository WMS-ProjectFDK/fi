<?php
	session_start();
	$result = array();

	include("../connect/conn.php");

	$rowno=0;
	$rs = "select a.id,a.gr_no,c.gr_date,a.line_no,a.qty,a.rack,a.item_no,a.pallet,a.tanggal,b.description, d.company from ztb_wh_in_det a
		left join item b on a.item_no=b.item_no left join gr_header c on a.gr_no=c.gr_no 
		left join company d on c.supplier_code=d.company_code and d.company_type=3 
		where a.rack is not null and a.qty - a.qty_out > 0
		order by id desc";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$qty = $items[$rowno]->QTY;
		$items[$rowno]->QTY = $qty;
		$items[$rowno]->QTY_A = number_format($qty);

		/*$date = date_create($items[$rowno]->TANGGAL);
		$items[$rowno]->TANGGAL = date_format($date,"Y - M - d");*/
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>