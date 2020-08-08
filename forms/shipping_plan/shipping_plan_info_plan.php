<?php
	include("../../connect/conn.php");
	ini_set('max_execution_time', -1);
	session_start();
	$items = array();
	$rowno=0;

	$work_order = isset($_REQUEST['work_order']) ? strval($_REQUEST['work_order']) : '';
	$ppbe = isset($_REQUEST['ppbe']) ? strval($_REQUEST['ppbe']) : '';
	
	$cek = "select '1' AM ,a.* ,'1' DEL, ROW_NUMBER() over (order by answer_no asc) RD, b.answer_no, b.crs_remark,
		cast(a.eta as varchar) as eta_format, cast(a.etd as varchar) as etd_format, cast(a.ex_fact as varchar) as ex_fact_format
		from ztb_shipping_plan a
		inner join answer b on a.wo_no = b.work_no and a.si_no = b.si_no and b.crs_remark=a.inv_no
		where wo_no = '$work_order' and b.crs_remark = '$ppbe'" ;
	$data_cek = sqlsrv_query($connect, strtoupper($cek));

	while($row = sqlsrv_fetch_object($data_cek)){
		array_push($items, $row);
		$Q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($Q);
		$rowno++;
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
?>