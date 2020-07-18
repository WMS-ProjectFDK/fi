<?php
	session_start();
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$cmb_di_no = isset($_REQUEST['cmb_di_no']) ? strval($_REQUEST['cmb_di_no']) : '';
	$ck_di_no = isset($_REQUEST['ck_di_no']) ? strval($_REQUEST['ck_di_no']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$txt_item_name = isset($_REQUEST['txt_item_name']) ? strval($_REQUEST['txt_item_name']) : '';
	$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';


	if ($ck_di_no != "true"){
		$di_no = "a.di_no ='$cmb_di_no' and ";
	}else{
		 $di_no = "";
	}

	if ($ck_item_no != "true"){
		$item = "c.item_no='$cmb_item_no' and ";
	}else{
		$item = "";
	}

	if ($src !='') {
		$where="";
	}else{
		$where ="where $di_no $item a.di_date between CAST('$date_awal' as date) and CAST('$date_akhir' as date)";
	}
	
	include("../../connect/conn.php");

	$sql = "select distinct a.di_no, a.di_date, a.req, a.shipto_code, b.company, a.person_code, a.upto_date from di_header a
		left join company b on a.shipto_code=b.company_code
		left join di_details c on a.di_no=c.di_no
		$where order by a.di_date desc";
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