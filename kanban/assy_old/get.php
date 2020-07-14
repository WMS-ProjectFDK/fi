<?php
	session_start();
	$id_kanban = $_SESSION['id_kanban'];

	include("../../connect/conn.php");

	$sql = "select * from (select a.* from ztb_assy_kanban a
			where a.worker_id1='$id_kanban' order by a.id desc) where rownum <=5";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$qty_act_p = $items[$rowno]->QTY_ACT_PERPALLET;
		$items[$rowno]->QTY_ACT_PERPALLET = number_format($qty_act_p);

		$qty_act_b = $items[$rowno]->QTY_ACT_PERBOX;
		$items[$rowno]->QTY_ACT_PERBOX = number_format($qty_act_b);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>