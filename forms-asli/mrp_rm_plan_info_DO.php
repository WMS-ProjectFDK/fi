<?php
	ini_set('max_execution_time', -1);
	session_start();
	//item_no=1170117&tgl_plan=2017-11-26
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	$tgl_plan = isset($_REQUEST['tgl_plan']) ? strval($_REQUEST['tgl_plan']) : '';

	include("../connect/conn.php");

	$cek = "select a.slip_no, a.slip_date, a.item_no, a.item_name, a.item_description, a.slip_quantity, nvl(b.rack,'-') as rack
		from transaction a
		left join ztb_wh_out_det b on a.slip_no = 'MT-'||b.slip_no
		where a.item_no=$item_no and TO_CHAR(a.slip_date,'YYYY-MM-DD')= '$tgl_plan' " ;
	$data_cek = oci_parse($connect, $cek);
	oci_execute($data_cek);

	$items = array();
	$rowno=0;

	while($row = oci_fetch_object($data_cek)){
		array_push($items, $row);
		$Q = $items[$rowno]->SLIP_QUANTITY;
		$items[$rowno]->SLIP_QUANTITY = '<b>'.number_format($Q).'</b>';

		$i = $items[$rowno]->ITEM_NAME;
		$d = $items[$rowno]->ITEM_DESCRIPTION;

		$items[$rowno]->DESCRIPTION = $i.'<br/>'.$d;
		
		$rowno++;
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
?>