<?php
	session_start();
	$result = array();
	include("../../connect/conn.php");
	$s_item =  isset($_REQUEST['s_item']) ? strval($_REQUEST['s_item']) : '';

	$rowno=0;
	$rs = "select a.item_no, b.item, b.description, c.unit
		from ztb_safety_stock a
		left join item b on a.item_no = b.item_no
		left join unit c on b.uom_q = c.unit_code
		where a.period=0 and a.year='MSTR'
		order by b.item";
	$data = sqlsrv_query($connect, $rs);
	
	$items = array();

	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);

		$items[$rowno]->del_link = '<a href="javascript:void(0)" onclick="safety_delete('.$items[$rowno]->item_no.')" style="color: red;">DELETE</a>';
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>