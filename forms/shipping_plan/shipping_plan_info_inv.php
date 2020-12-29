<?php
	error_reporting(0);
	ini_set('max_execution_time', -1);
	session_start();

	$work_order = isset($_REQUEST['work_order']) ? strval($_REQUEST['work_order']) : '';

	include("../../connect/conn.php");
	$cek = "select do_so.customer_po_no, 
		cast(ETD as varchar(10)) as ETD, 
		cast(ETA as varchar(10)) as ETA, 
		cast(cr_date as varchar(10)) as cr_date, 
		do_no, line_no, answer.item_no, answer.qty,
		case when INDICATION.COMMIT_DATE is not null then 'DELIVERY UPDATE' else '' end as sts_stock
		from answer 
		left join do_so on do_so.answer_no = answer.answer_no 
		left join indication on INDICATION.inv_no = do_so.do_no
		where work_no = '$work_order'" ;
	$data_cek = sqlsrv_query($connect, strtoupper($cek));

	$items = array();
	$rowno=0;	$q_total = 0;

	while($row = sqlsrv_fetch_object($data_cek)){
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