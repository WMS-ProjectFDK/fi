<?php
	session_start();
	include("../connect/conn.php");
	$result = array();
	$items = array();
	$si = isset($_REQUEST['si']) ? strval($_REQUEST['si']) : '';

	$rs = "select mh.work_order, mh.po_no, mh.po_line_no, mh.cr_date, mh.batery_type, mh.cell_grade, mh.item_no, mh.item_name,  
		nvl(mh.qty,0) as qty_order, nvl(pi.qty_prod,0) as qty_produksi, nvl(zt.qty_plan,0) qty_plan, nvl(inv.qty_invoiced,0) qty_invoiced,
    	so.so_no, so.line_no, so.curr_code, so.u_price
		from mps_header mh
		left outer join (select wo_no,sum(slip_quantity) qty_prod from production_income group by wo_no) pi on mh.work_order = pi.wo_no
		left outer join (select sum(nvl(do_so.qty,0)) qty_invoiced, work_no, crs_remark, si_no, stuffy_date, etd, eta from answer 
			                     left join do_so on do_so.answer_no = answer.answer_no 
			                     group by work_no, crs_remark, si_no, stuffy_date, etd, eta)inv on mh.work_order = inv.work_no
		left outer join (select wo_no,sum(qty) qty_plan, nvl(count(nvl(wo_no,1)),1) ship from ztb_shipping_plan group by wo_no) zt on zt.wo_no = mh.work_order
		left join (select soh.customer_code, soh.so_no, soh.customer_po_no, soh.curr_code, sod.u_price, sod.line_no,sod.item_no from so_header soh
	               inner join so_details sod on soh.so_no=sod.so_no) so on mh.po_no = so.customer_po_no and mh.item_no = so.item_no
		where mh.po_no in (select po_no from si_po where si_no='$si')
		and nvl(mh.qty,0) - nvl(zt.qty_plan,0) > 0";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	
	while($row = oci_fetch_object($data)) {	
		array_push($items, $row);
		$rowno++;
	}

	$result["rows"] = $items;
	echo json_encode($result);
?>