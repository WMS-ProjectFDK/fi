<?php
	session_start();
	include("../../connect/conn.php");

	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
	$cmb_slip_no = isset($_REQUEST['cmb_slip_no']) ? strval($_REQUEST['cmb_slip_no']) : '';
	$ck_slip_no = isset($_REQUEST['ck_slip_no']) ? strval($_REQUEST['ck_slip_no']) : '';
	$cmb_slip_type = isset($_REQUEST['cmb_slip_type']) ? strval($_REQUEST['cmb_slip_type']) : '';
	$ck_slip_type = isset($_REQUEST['ck_slip_type']) ? strval($_REQUEST['ck_slip_type']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$txt_item_name = isset($_REQUEST['txt_item_name']) ? strval($_REQUEST['txt_item_name']) : '';
	$ck_sts_approval = isset($_REQUEST['ck_sts_approval']) ? strval($_REQUEST['ck_sts_approval']) : '';
	$cmb_sts_approval = isset($_REQUEST['cmb_sts_approval']) ? strval($_REQUEST['cmb_sts_approval']) : '';
	$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

	if ($ck_date != "true"){
		$date = "a.slip_date between '$date_awal' and '$date_akhir' and ";
	}else{
		$date = " ";
	}

	if ($ck_slip_no != "true"){
		$slip_no = "a.slip_no = '$cmb_slip_no' and ";
	}else{
		$slip_no = " ";
	}

	if ($ck_slip_type != "true"){
		$slip_type = "a.slip_type = '$cmb_slip_type' and ";
	}else{
		$slip_type = " ";
	}

	if ($ck_item_no != "true"){
		$item_no = "a.slip_no in (select slip_no from MTE_DETAILS where ITEM_NO = '$cmb_item_no' ) and ";
	}else{
		$item_no = " ";
	}

	$sts_appr = '';
	if ($ck_sts_approval != "true"){
		if($cmb_sts_approval=='0'){
			$sts_appr = "a.approval_date is null and ";
		}elseif($cmb_sts_approval=='1'){
			$sts_appr = "a.approval_date is not null and ";
		}
	}else{
		 $item_name = " ";
	}

	if ($src != '') {
		$where = "where a.item_no like '%$src%' ";
	}else{
		$where = "where a.item_no is not null ";
	}

	$sql = "select top 200 * from item";
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;

	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>