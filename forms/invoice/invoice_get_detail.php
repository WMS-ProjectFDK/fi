<?php
	session_start();
	$result = array();

	$do = isset($_REQUEST['do']) ? strval($_REQUEST['do']) : '';

	include("../../connect/conn.php");

	$rowno=0;
	$rs = "select b.line_no, a.do_no, b.item_no, c.description, c.uom_q, d.unit_pl, b.qty, b.u_price, b.amt_o, b.amt_l 
		from do_header a
		inner join do_details b on a.do_no=b.do_no
		inner join item c on b.item_no=c.item_no
		inner join unit d on c.uom_q=d.unit_code
		where a.do_no='$do'
		order by b.line_no asc";
	$data = sqlsrv_query($connect, strtoupper($rs));
	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$q = $items[$rowno]->QTY;
		$bq = $items[$rowno]->BAL_QTY;
		$gq = $items[$rowno]->GR_QTY;
		$o = $items[$rowno]->AMT_O;
		$l = $items[$rowno]->AMT_L;
		$items[$rowno]->AMT_O = number_format($o,2);
		$items[$rowno]->AMT_L = number_format($l,2);
		$items[$rowno]->QTY = number_format($q);
		$items[$rowno]->BAL_QTY = number_format($bq);
		$items[$rowno]->GR_QTY = number_format($gq);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>