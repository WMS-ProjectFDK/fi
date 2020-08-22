<?php
	session_start();
	$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

	if ($src !='') {
		$where="where work_order like '%$src%'";
	}else{
		$where="where work_order like '%$src%'";
	}
	
	include("../../connect/conn.php");

	$sql = "select a.work_order, a.item_no, c.description, so.asin, so.amazon_po_no, ceiling((a.Qty/d.pallet_unit_number ) ) as TotalPallet
		from mps_header a
		inner join (select ax.so_no, ax.customer_po_no, bx.line_no, bx.item_no, bx.qty, bx.asin, bx.amazon_po_no
		    from so_header ax
		    inner join so_details bx on ax.so_no=bx.so_no
		    ) so on a.po_no = so.customer_po_no and a.po_line_no = so.line_no
		inner join item c on a.item_no = c.item_no 
		inner join packing_information d on c.pi_no = d.pi_no 
		$where";
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>