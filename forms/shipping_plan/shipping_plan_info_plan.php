<?php
	include("../../connect/conn.php");
	ini_set('max_execution_time', -1);
	session_start();
	$items = array();
	$rowno=0;

	$work_order = isset($_REQUEST['work_order']) ? strval($_REQUEST['work_order']) : '';
	$ppbe = isset($_REQUEST['ppbe']) ? strval($_REQUEST['ppbe']) : '';
	
	$cek = "select a.WO_NO, a.ITEM_NO, a.ITEM_NAME, a.SI_NO, 
		cast(a.CR_DATE as varchar(10)) as CR_DATE, 
		cast(a.ETA as varchar(10)) as eta_format,
		cast(a.ETD as varchar(10)) as etd_format,
		cast(a.EX_FACT as varchar(10)) as ex_fact_format,
		a.QTY, a.SO_NO, a.LINE_NO, a.VESSEL, a.PO_NO, a.INV_NO, a.INV_LINE_NO, a.ROW_ID as RD,
		b.answer_no, b.crs_remark
		from ZTB_SHIPPING_PLAN a
		inner join answer b on a.wo_no = b.work_no and a.si_no = b.si_no and b.crs_remark=a.inv_no
		where a.wo_no = '$work_order' and b.crs_remark = '$ppbe'" ;
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