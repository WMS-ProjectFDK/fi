<?php
	session_start();
	$result = array();


	include("../connect/conn_spareparts.php");

	include("../connect/conn_memsys.php");
	$rowno=0;

	// $rs = "select i.item_no,i.item,REPLACE(i.description,'-', ' ') description,i.cost_process_code,nvl(i.item_type2, w.rack_addr) as item_type2,i.item_type2 as i_item_type2,
	// 	(select max(level_no) from structure where upper_item_no=i.item_no) as level_no,u.unit
	// 	from item i
	// 	left join whinventory w on i.item_no=w.item_no
	// 	left join unit u on i.uom_q=u.unit_code
	// 	$where
	// 	order by i.description";
	// $data = oci_parse($connect, $rs);
	// oci_execute($data);
	// $items = array();
	// while($row = oci_fetch_object($data)) {
	// 	array_push($items, $row);
	// 	$rowno++;
	// }
	// $result["rows"] = $items;
	// echo json_encode($result);
?>