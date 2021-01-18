<?php
	session_start();
	include("../connect/conn.php");

	$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';

	$sql = "select 'MT-'+a.slip_no as slip_no, a.item_no, i.description, a.qty, a.rack, a.wo_date, 
		a.line_no as incoming_no, a.pallet, a.stat
		from ztb_wh_out_det a
		left join item i on a.item_no=i.item_no
		where a.id_1=$id and a.item_no=$item_no
		order by a.line_no asc";
	$data = sqlsrv_query($connect, strtoupper($sql));
	

	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>