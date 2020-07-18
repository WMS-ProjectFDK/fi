<?php
	session_start();
	$result = array();

	$di = isset($_REQUEST['di']) ? strval($_REQUEST['di']) : '';

	include("../connect/conn.php");

	$rowno=0;
	$rs = "select distinct a.po_no, a.po_line_no, a.origin_code, a.item_no, b.item, b.description, a.uom_q, 
		case e.sts_bundle when 'Y' then 'BUNDLE' else c.unit end as unit,
		a.qty, a.data_date, d.po_date
		from di_details a
		inner join item b on a.item_no=b.item_no
		inner join unit c on a.uom_q= c.unit_code
		inner join po_header d on a.po_no=d.po_no
		left join ztb_safety_stock e on a.item_no=e.item_no
		where a.di_no='$di' order by b.description asc, d.po_date asc";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$q=$items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>