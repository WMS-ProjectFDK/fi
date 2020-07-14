<?php
	session_start();
	include("../connect/conn.php");
	$result = array();
	$rowno=0;
	$items = array();

	$ppbe = isset($_REQUEST['ppbe']) ? strval($_REQUEST['ppbe']) : '';
	$si = isset($_REQUEST['si']) ? strval($_REQUEST['si']) : '';

	$rs = "select distinct a.work_no as work_order, a.customer_po_no as po_no, a.customer_po_line_no as po_line_no, a.cr_date,
		a.item_no, mh.item_name, nvl(mh.qty,0) as qty_order, nvl(a.qty,0) qty_plan,
		a.so_line_no as line_no, a.curr_code, a.u_price, a.answer_no as sts
		from answer a
		inner join mps_header mh on a.work_no = mh.work_order
		where a.si_no='$si' and a.crs_remark='$ppbe'";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
	}

	$result["rows"] = $items;
	echo json_encode($result);
?>