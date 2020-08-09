<?php
	session_start();
	$ppbe = isset($_REQUEST['ppbe']) ? strval($_REQUEST['ppbe']) : '';
	$items = array();
	$rowno=0;
	include("../../connect/conn.php");
	
	$sql = "select rowid ROW_ID, 'OLD' as sts, a.so_no, a.wo_no, c.description, a.item_no,
	a.qty, a.pallet, a.carton, a.carton_non_full,
	a.gw gross, 
	a.nw net, 
	msm,
	a.answer_no, a.start_box, a.end_box
	from ztb_shipping_ins a
	inner join item c on a.item_no = c.item_no
		where a.remarks='$ppbe'
		order by line_no asc";

	$data = sqlsrv_query($connect, strtoupper($sql));
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$q = $items[$rowno]->ITEM_NO;
		$items[$rowno]->ITEM_NO = '<a href="javascript:void(0)" title="ITEM_NO" onclick="info_item('.$q.')"  style="text-decoration: none; color: black;">'.$q.'</a>';
		$rowno++;
	}

	$result["rows"] = $items;
	echo json_encode($result);
?>