<?php
	session_start();
	include("../connect/conn.php");
	$items = array();
	$rowno=0;

	$sql = "select tipe, aging, safety_day, grade, working_day, to_char(total_order,'9,999,999,990') total_order, 
		to_char(order_per_day,'9,999,999,990') order_per_day, to_char(average,'9,999,999,990.00') average,
		nvl(to_char(heating_room,'9,999,999,990'),0) heating_room, to_char(std_minimum,'9,999,999,990') std_minimum,
		to_char(balance,'9,999,999,990') balance, to_char(before_label,'9,999,999,990') before_label, 
		to_char(after_label,'9,999,999,990') after_label, to_char(suspended,'9,999,999,990') suspended,
		tipe2, to_char(qty_aging,'9,999,999,990') qty_aging
		from ztb_semi_bat order by tipe";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>