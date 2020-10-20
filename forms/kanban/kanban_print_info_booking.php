<?php
	error_reporting(0);
	ini_set('max_execution_time', -1);
	session_start();
	//item_no=1170117&tgl_plan=2017-11-26
    $item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
    $items = array();		$foot = array();
	$rowno=0;				$q_total = 0;
	
	include("../../connect/conn.php");

	$cek = "select distinct WO_NO, sum(qty) as QTY from ZTB_ITEM_BOOK
        where item_no = $item_no
        group by WO_NO";
    // echo $cek;
	$data_cek = sqlsrv_query($connect, strtoupper($cek));

	while($row = sqlsrv_fetch_object($data_cek)){
		array_push($items, $row);
		$Q = $items[$rowno]->QTY;
		$q_total += $Q;
		$items[$rowno]->QTY = '<b>'.number_format($Q).'</b>';
		$rowno++;
	}

	$foot[0]->QTY = '<span style="color:blue;font-size:12px;"><b>'.number_format($q_total).'</b></span>';

	$result["rows"] = $items;
	$result["footer"] = $foot;
	
	$result["rows"] = $items;
	echo json_encode($result);
?>