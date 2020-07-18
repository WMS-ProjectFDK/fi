<?php
	session_start();
	$result = array();
	$items = array();
	$rowno=0;
	$supp = isset($_REQUEST['supp']) ? strval($_REQUEST['supp']) : '';
	$by = isset($_REQUEST['by']) ? strval($_REQUEST['by']) : ''; 
	$po = isset($_REQUEST['po']) ? strval($_REQUEST['po']) : '';

	if($by == "PO_NO"){
		$bypil = " a.po_no like '%".strtoupper($po)."%'";
	}elseif($by == "ITEM_NO"){
		$bypil = " a.item_no like '%".strtoupper($po)."%' ";
	}

	include("../../connect/conn.php");

	$rs = "select a.po_no, cast(b.po_date as varchar(10)) po_date, a.item_no, c.item, c.description, c.stock_subject_code, c.cost_process_code, c.cost_subject_code, c.class_code,
		c.standard_price, COALESCE(c.suppliers_price,0) AS suppliers_price, a.origin_code, d.country, a.line_no, a.qty, a.gr_qty, 
		(a.qty-a.gr_qty) as bal_qty, b.curr_code,e.curr_mark, e.curr_short,  a.uom_q, f.unit, b.ex_rate, cast(a.eta as varchar(10)) as eta,
		coalesce(b.pdays,g.pdays) as pdays, coalesce(b.pdesc,g.pdesc) as pdesc, g.country_code, h.country as country_slip, 
		(CASE WHEN h.country='INDONESIA' THEN '11' ELSE '01' END) as slip_type, 
		a.u_price, (CASE WHEN a.item_no=whi.item_no THEN '1' ELSE '0' END) as sts_wh
		from po_details a 
		inner join po_header b on a.po_no= b.po_no
		left join item c on a.item_no=c.item_no
		left join country d on a.origin_code=d.country_code
		left join currency e on b.curr_code=e.curr_code
		left join unit f on a.uom_q=f.unit_code
		left join company g on b.supplier_code=g.company_code
		left join country h on g.country_code=h.country_code
		left join whinventory whi on c.item_no=whi.item_no
		where b.supplier_code='$supp' 
		and a.qty-a.gr_qty > 0 and
		$bypil
		and a.eta >= dateadd(d,-90,getdate())
		order by a.eta asc";
		
	$data = sqlsrv_query($connect, strtoupper($rs));
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$qty = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($qty);
		$items[$rowno]->QTY_2 = number_format($qty);

		$gr_qty = $items[$rowno]->GR_QTY;
		$items[$rowno]->GR_QTY = number_format($gr_qty);
		$items[$rowno]->GR_QTY_2 = number_format($gr_qty);

		$bal_qty = $items[$rowno]->BAL_QTY;
		$items[$rowno]->BAL_QTY = number_format($bal_qty);

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>