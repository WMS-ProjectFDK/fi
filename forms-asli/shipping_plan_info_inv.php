<?php
	ini_set('max_execution_time', -1);
	session_start();

	$work_order = isset($_REQUEST['work_order']) ? strval($_REQUEST['work_order']) : '';

	include("../connect/conn.php");
	$cek = "select do_so.customer_po_no, ETD, ETA, cr_date, do_no, line_no, answer.item_no, answer.qty 
		from answer 
		inner join do_so on do_so.answer_no = answer.answer_no 
		where work_no = '$work_order'" ;
	$data_cek = oci_parse($connect, $cek);
	oci_execute($data_cek);

	$items = array();
	$rowno=0;	$q_total = 0;

	while($row = oci_fetch_object($data_cek)){
		array_push($items, $row);
		$Q = $items[$rowno]->QTY;
		$q_total += $Q;
		$items[$rowno]->QTY = number_format($Q);
		$rowno++;
	}

	$foot[0]->QTY = '<span style="color:blue;font-size:12px;"><b>'.number_format($q_total).'</b></span>';
	
	$result["rows"] = $items;
	$result["footer"] = $foot;
	echo json_encode($result);
?>