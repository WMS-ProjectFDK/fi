<?php
	error_reporting(0);
	ini_set('max_execution_time', -1);
	session_start();
	//item_no=1170117&tgl_plan=2017-11-26
	$work_order = isset($_REQUEST['work_order']) ? strval($_REQUEST['work_order']) : '';
	

	include("../../connect/conn.php");

	$cek = "select pi.wo_no,plt_no,pi.Item_no, item_description, remark2 Scan_Date,
		case cast(slip_type as varchar(10)) when '80' then 'KURAIRE' else 'KURADASHI' end Slip_Type,slip_quantity , cast(approval_date as nvarchar(10)) as approval_date 
		from  production_income pi 
		left outer join ztb_p_plan on cast(id as varchar(10)) = slip_no where pi.wo_no = '$work_order'
		order by plt_no" ;

	$data_cek = sqlsrv_query($connect, strtoupper($cek));

	$items = array();
	$rowno=0;	$q_total = 0;

	while($row = sqlsrv_fetch_object($data_cek)){
		array_push($items, $row);
		$Q = $items[$rowno]->SLIP_QUANTITY;
		$q_total += $Q;
		$items[$rowno]->SLIP_QUANTITY = '<b>'.number_format($Q).'</b>';
		$rowno++;
	}

	$foot[0]->SLIP_QUANTITY = '<span style="color:blue;font-size:12px;"><b>'.number_format($q_total).'</b></span>';

	$result["rows"] = $items;
	$result["footer"] = $foot;
	
	$result["rows"] = $items;
	echo json_encode($result);
?>