<?php
	ini_set('max_execution_time', -1);
	session_start();
	//item_no=1170117&tgl_plan=2017-11-26
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	$tgl_plan = isset($_REQUEST['tgl_plan']) ? strval($_REQUEST['tgl_plan']) : '';

	include("../../../connect/conn.php");

	$cek = "select a.slip_no, cast(a.slip_date as varchar(10))  as slip_date, a.item_no, a.item_name, a.item_description, a.slip_quantity, isnull(b.rack,'-') as rack
		from [transaction] a
		left join ztb_wh_out_det b on a.slip_no = 'MT-'+ b.slip_no
		where a.item_no=$item_no and a.slip_date = '$tgl_plan' 
		
		" ;
    
	$data_cek = sqlsrv_query($connect, strtoupper($cek));
	

	$items = array();
	$rowno=0;

	while($row = sqlsrv_fetch_object($data_cek)){
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