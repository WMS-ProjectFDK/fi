<?php
	include("../../connect/conn.php");
	// header("Content-type: application/json");

	$do = isset($_REQUEST['do_no']) ? strval($_REQUEST['do_no']) : '';

	$sql = "select d.do_no, d.description PPBE, z.carton, z.pallet,z.qty, z.gw,z.nw,z.msm from do_header d
	inner join indication i
	on d.do_no = i.inv_no
	inner join ztb_shipping_ins z
	on z.answer_no = i.answer_no
	where eta = (select eta from do_header where do_no = '$do')";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($items);
?>