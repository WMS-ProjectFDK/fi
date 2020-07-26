<?php
	ini_set('max_execution_time', -1);
	session_start();
	//item_no=1170117&tgl_plan=2017-11-26
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';

	include("../../../connect/conn.php");

	$cek = "select a.po_no, a.line_no, a.item_no, b.item, b.description, cast(a.eta as varchar(10)) eta,
		a.qty, a.gr_qty, a.bal_qty
		from po_details a
		inner join item b on a.item_no=b.item_no
		where a.eta < cast(getdate() as date) and a.eta > '01-JAN-18' and a.item_no = $item_no and bal_qty!=0" ;
	$data_cek = sqlsrv_query($connect, strtoupper($cek));
	
	//echo $cek;
	$items = array();
	$rowno=0;

	while($row = sqlsrv_fetch_object($data_cek)){
		array_push($items, $row);
		$Q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($Q);

		$RECEIVE = $items[$rowno]->GR_QTY;
		$items[$rowno]->GR_QTY = number_format($RECEIVE);

		$OST = $items[$rowno]->BAL_QTY;
		$items[$rowno]->BAL_QTY = '<b>'.number_format($OST).'</b>';

		$i = $items[$rowno]->ITEM;
		$d = $items[$rowno]->DESCRIPTION;

		$items[$rowno]->DESCRIPTION = $i.'<br/>'.$d;		

		$rowno++;
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
?>