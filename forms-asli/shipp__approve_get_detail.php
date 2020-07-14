<?php
	session_start();
	include("../connect/conn.php");
	$result = array();

	$req = isset($_REQUEST['req']) ? strval($_REQUEST['req']) : '';

	$rowno=0;
	$rs = "select a.item_no, b.description, a.so_no, a.so_line_no, a.work_no, a.answer_no, a.customer_po_no,
		nvl(c.qty,0) qty_order, nvl(sum(d.slip_quantity),0) qty_Produksi, a.qty as qty_plan
		from answer a
		inner join item b on a.item_no = b.item_no
		left join mps_header c on a.work_no = c.work_order
		left join production_income d on a.work_no = d.wo_no
		where a.CRS_REMARK='$req'
		group by a.item_no, b.description, a.so_no, a.so_line_no, a.work_no, a.answer_no, a.qty, a.customer_po_no, c.qty";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$qty_or = $items[$rowno]->QTY_ORDER;
		$items[$rowno]->QTY_ORDER = number_format($qty_or);
		$qty_pr = $items[$rowno]->QTY_PRODUKSI;
		$items[$rowno]->QTY_PRODUKSI = number_format($qty_pr);
		$qty_pl = $items[$rowno]->QTY_PLAN;
		$items[$rowno]->QTY_PLAN = number_format($qty_pl);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>