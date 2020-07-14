<?php
	session_start();
	$result = array();

	$gr_no = isset($_REQUEST['gr_no']) ? strval($_REQUEST['gr_no']) : '';

	include("../connect/conn.php");

	$rowno=0;
	$rs = "select a.*,b.description, c.unit, to_char(a.u_price,'99,999,990.00000') as u_price,
		to_char(a.amt_l,'99,999,990.00000') as amt_l
		from gr_details a 
		inner join item b on a.item_no=b.item_no inner join unit c on a.uom_q = c.unit_code
		where a.gr_no='$gr_no' order by a.line_no asc";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q,2);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>