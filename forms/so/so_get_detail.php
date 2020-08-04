<?php
	session_start();
	$result = array();

	$so_no = isset($_REQUEST['so_no']) ? strval($_REQUEST['so_no']) : '';

	include("../../connect/conn.php");

	$rowno=0;
	$rs = "select a.*,b.description, c.unit, CONVERT(DECIMAL(16,5), a.u_price) as u_price, CONVERT(decimal(16,3), a.amt_l) as amt_l,
        a.CASE_MARK_1+'<br/>'+a.case_mark_2+'<br/>'+a.case_mark_3+'<br/>'+a.case_mark_4+'<br/>'+a.case_mark_5+'<br/>'+
        a.case_mark_6+'<br/>'+a.case_mark_7+'<br/>'+a.case_mark_8+'<br/>'+a.case_mark_9+'<br/>'+a.case_mark_10 as case_mark,
        a.PALLET_MARK_1+'<br/>'+a.PALLET_MARK_2+'<br/>'+a.PALLET_MARK_3+'<br/>'+a.PALLET_MARK_4+'<br/>'+a.PALLET_MARK_5+'<br/>'+
        a.PALLET_MARK_6+'<br/>'+a.PALLET_MARK_7+'<br/>'+a.PALLET_MARK_8+'<br/>'+a.PALLET_MARK_9+'<br/>'+a.PALLET_MARK_10 as pallet_mark
        from so_details a 
        left join item b on a.item_no=b.item_no 
        left join unit c on a.uom_q = c.unit_code
        where a.so_no='$so_no' 
        order by a.line_no asc";
	$data = sqlsrv_query($connect, strtoupper($rs));
	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q,2);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>