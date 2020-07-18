<?php
	session_start();
	$result = array();
	
	$po_no = isset($_REQUEST['po_no']) ? strval($_REQUEST['po_no']) : '';

	include("../../connect/conn.php");
	$rowno=0;
	$rs = "select a.item_no, a.line_no, b.item, b.description, a.uom_q, c.unit, a.origin_code, d.country, a.u_price as ESTIMATE_PRICE, a.carved_stamp,
		po.curr_code, e.curr_short, e.curr_mark, cast(a.eta as varchar(10)) as eta_date, a.QTY, a.gr_qty, a.bal_qty, a.prf_no, a.prf_no as prf_nomor, a.PRF_LINE_NO
		from po_details a
		left join po_header po on a.po_no=po.po_no
		left join item b on a.item_no=b.item_no
		left join unit c on a.uom_q=c.unit_code
		left join country d on a.origin_code= d.country_code
		left join currency e on po.curr_code=e.curr_code
		where po.po_no='$po_no'
		order by a.line_no asc";

	$data = sqlsrv_query($connect, strtoupper($rs));
	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$q = $items[$rowno]->QTY;
		$items[$rowno]->qty = number_format($q,2);

		$qb = $items[$rowno]->BAL_QTY;
		$items[$rowno]->bal_qty = number_format($qb,2);

		$qg = $items[$rowno]->GR_QTY;
		$items[$rowno]->gr_qty = number_format($qg,2);

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>