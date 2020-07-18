<?php
	session_start();
	$result = array();
	
	$gr_no = isset($_REQUEST['gr_no']) ? strval($_REQUEST['gr_no']) : '';

	include("../../connect/conn.php");
	$rowno=0;

	$rs = "select distinct b.line_no as line_no_gr, a.gr_no, b.po_line_no as line_no_po, b.po_no, g.po_date, b.item_no, c.item, c.description, c.STOCK_SUBJECT_CODE, c.COST_PROCESS_CODE, c.COST_SUBJECT_CODE, c.STANDARD_PRICE, coalesce(c.SUPPLIERS_PRICE,0) as SUPPLIERS_PRICE,
		b.uom_q, d.unit, a.curr_code, e.curr_mark, e.curr_short, f.qty, f.gr_qty - b.qty as gr_qty, b.qty as act_qty, f.origin_code, b.u_price, a.ex_rate, f.eta,
		a.pdays, a.pdesc, b.amt_o, b.amt_l, a.slip_type, (CASE WHEN b.item_no=whi.item_no THEN '1' ELSE '0' END) as sts_wh, f.qty - (f.gr_qty - b.qty) as blnc
		from gr_header a
		inner join gr_details b on a.gr_no=b.gr_no
		left join item c on b.item_no=c.item_no
		left join unit d on b.uom_q=d.unit_code
		left join currency e on a.curr_code = e.curr_code
		left join po_details f on b.po_no= f.po_no and b.item_no=f.item_no and b.po_line_no= f.line_no
		left join po_header g on f.po_no=g.po_no
		left join whinventory whi on b.item_no= whi.item_no
		where a.gr_no='$gr_no' order by b.line_no asc";
	$data = sqlsrv_query($connect, strtoupper($rs));
	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$qty = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($qty);
		$items[$rowno]->QTY_2 = number_format($qty);

		$gr_qty = $items[$rowno]->GR_QTY;
		$items[$rowno]->GR_QTY = number_format($gr_qty);
		$items[$rowno]->GR_QTY_2 = number_format($gr_qty);

		$act = $items[$rowno]->ACT_QTY;

		$bal_qty = $items[$rowno]->BAL_QTY;
		$items[$rowno]->BAL_QTY = number_format($bal_qty);
		$items[$rowno]->BAL_QTY_2 = number_format($bal_qty);

		$blnc = $items[$rowno]->BLNC;
		$items[$rowno]->BLNC = number_format($blnc);

		if($qty==$gr_qty){
			$items[$rowno]->GR_QTY = number_format($qty-$act);
			$items[$rowno]->GR_QTY_2 = number_format($qty-$act);
		}else{
			$items[$rowno]->GR_QTY = number_format($gr_qty);
			$items[$rowno]->GR_QTY_2 = number_format($gr_qty);
		}

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>