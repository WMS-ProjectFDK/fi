<?php
	session_start();
	$result = array();
	
	$po_no = isset($_REQUEST['po_no']) ? strval($_REQUEST['po_no']) : '';

	include("../connect/conn.php");
	$rowno=0;
	$rs = "select a.item_no, a.line_no, b.item, b.description, a.uom_q, c.unit, a.origin_code, d.country, a.u_price as ESTIMATE_PRICE, a.carved_stamp,
		po.curr_code, e.curr_short, e.curr_mark, to_char(a.eta,'YYYY-MM-DD') as eta_date, a.QTY, a.gr_qty, a.bal_qty, a.prf_no, a.prf_no as prf_nomor, a.PRF_LINE_NO
		from po_details a
		inner join po_header po on a.po_no=po.po_no
		inner join item b on a.item_no=b.item_no
		inner join unit c on a.uom_q=c.unit_code
		inner join country d on a.origin_code= d.country_code
		inner join currency e on po.curr_code=e.curr_code
		where po.po_no='$po_no'
		order by a.line_no asc";

	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q,2);

		$qb = $items[$rowno]->BAL_QTY;
		$items[$rowno]->BAL_QTY = number_format($qb,2);

		$qg = $items[$rowno]->GR_QTY;
		$items[$rowno]->GR_QTY = number_format($qg,2);

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>