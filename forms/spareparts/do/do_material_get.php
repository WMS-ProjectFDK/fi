<?php
	session_start();
	include("../connect/conn.php");

	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_do_date = isset($_REQUEST['ck_do_date']) ? strval($_REQUEST['ck_do_date']) : '';
	$cmb_mtno = isset($_REQUEST['cmb_mtno']) ? strval($_REQUEST['cmb_mtno']) : '';
	$ck_mtno = isset($_REQUEST['ck_mtno']) ? strval($_REQUEST['ck_mtno']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';

	if ($ck_do_date != "true"){
		$date = " wo_date between '$date_awal' and '$date_akhir' and ";
	}else{
		$date = "";
	}

	if ($ck_mtno != "true"){
		$mt = " cast(id_1 as integer) = '$cmb_mtno' and ";
	}else{
		$mt = "";
	}

	if ($ck_item_no != "true"){
		$item_no = " item_no='$cmb_item_no' and ";
	}else{
		$item_no = "";
	}
	
	$where =" where $date $mt $item_no wo_date is not null";
	
	$sql = "select * from (
		select distinct 'MT-'+id_1 as mt_no, wo_date, stat, cast(id_1 as integer) as id
		from ztb_wh_out_det
		$where
		order by wo_date desc, cast(id_1 as integer) desc)
		where rownum <= 500";
	//echo $sql;
	$data = sqlsrv_query($connect, $sql);

	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>