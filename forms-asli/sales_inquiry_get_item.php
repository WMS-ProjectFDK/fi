<?php
	session_start();
	$result = array();
	include("../connect/conn.php");
	$s_item =  isset($_REQUEST['s_item']) ? strval($_REQUEST['s_item']) : '';
	$rowno=0;
	$items = array();
	
	$rs = "select distinct a.item_no, b.item, b.description, u.unit, b.uom_q
		from mps_header a 
		left join item b on a.item_no=b.item_no
		inner join unit u on b.uom_q= u.unit_code
		where a.item_no like '%$s_item%'
		order by b.description";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>