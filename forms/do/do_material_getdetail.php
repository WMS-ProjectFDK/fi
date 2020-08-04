<?php
	session_start();
	include("../connect/conn.php");

	$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';

	$sql = "select a.item_no, i.description, sum(a.qty) as qty
		from ztb_wh_out_det a
		left join item i on a.item_no=i.item_no
		where a.id_1=$id
		group by a.item_no, i.description";
	$data = sqlsrv_query($connect, strtoupper($sql));
	

	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q);

		$items[$rowno]->VW_DETAILS = '<a href="javascript:void(0)" title="'.$v.'" onclick="info_rack('.$id.','.$items[$rowno]->ITEM_NO.')">INFO RACK & PRINT ULANG</a>';
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>