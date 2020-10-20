<?php
	session_start();
	$result = array();

	$gr_no = isset($_REQUEST['gr_no']) ? strval($_REQUEST['gr_no']) : '';

	include("../../connect/conn.php");

	$rowno=0;
	$rs = "select a.*,b.description, c.unit, CONVERT(DECIMAL(16,5), a.u_price) as u_price,
		CONVERT(decimal(16,3), a.amt_l) as amt_l
		from gr_details a 
		left join item b on a.item_no=b.item_no 
		left join unit c on a.uom_q = c.unit_code
		where a.gr_no='$gr_no' 
		order by a.line_no asc";
	$data = sqlsrv_query($connect, strtoupper($rs));
	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q,2);

		$amt_o = $items[$rowno]->AMT_O;
		$items[$rowno]->AMT_O = number_format($amt_o,2);
		
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>