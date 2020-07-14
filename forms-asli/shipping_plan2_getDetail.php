<?php
	session_start();
	include("../connect/conn.php");

	$ppbe = isset($_REQUEST['ppbe']) ? strval($_REQUEST['ppbe']) : '';
	$si = isset($_REQUEST['si']) ? strval($_REQUEST['si']) : '';

	$sql = "select a.customer_code, c.company, a.customer_po_no, a.so_no, a.so_line_no, 
		a.item_no, i.item, i.description, a.work_no, a.cr_date, a.answer_no,
		nvl(b.qty,0) as qty, nvl(qty_prod,0) qty_produksi, nvl(inv.qty_invoiced,0) qty_invoiced, nvl(qty_plan,0) qty_plan
		from answer a
		inner join mps_header b on a.work_no = b.work_order
		inner join company c on a.customer_code = c.company_code
		inner join item i on a.item_no = i.item_no
		left outer join (select wo_no,sum(slip_quantity) qty_prod from production_income group by wo_no) pi on a.work_no = pi.wo_no
		left outer join (select sum(nvl(do_so.qty,0)) qty_invoiced, answer_no from do_so group by answer_no)inv on a.answer_no = inv.answer_no
		left outer join (select wo_no,sum(Qty) qty_plan, nvl(count(nvl(wo_no,1)),1) ship from ztb_shipping_plan group by wo_no)zt on zt.wo_no = a.work_no
		where a.crs_remark='$ppbe' and a.si_no='$si'
		order by a.so_no asc, a.so_line_no asc";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>