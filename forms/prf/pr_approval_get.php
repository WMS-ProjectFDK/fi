<?php
	session_start();
	include("../../connect/conn.php");

	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$cmb_prf_no = isset($_REQUEST['cmb_prf_no']) ? strval($_REQUEST['cmb_prf_no']) : '';
	$ck_prf = isset($_REQUEST['ck_prf']) ? strval($_REQUEST['ck_prf']) : '';

	if ($ck_date != "true"){
		$date = "a.prf_date between '$date_awal' and '$date_akhir'  and ";
	}else{
		$date = "";
	}

	if ($ck_item_no != "true"){
		$item_no = " exists(select * from PRF_DETAILS where item_no = $cmb_item_no and prf_no = a.PRF_NO) and ";
	}else{
		$item_no = "";
	}

	if ($ck_prf != "true"){
		$prf = "a.prf_no = '".trim($cmb_prf_no)."' and ";
	}else{
		$prf = "";
	}	

	
	$where ="where $item_no $prf $date a.section_code=100 and approval_date is null and approval_person_code is null";
	

  	$sql  = "select top 150  a.prf_no, cast(a.prf_date as varchar(10)) as prf_date, a.section_code, replace(a.remark,char(10),'<br>') as remark, a.require_person_code,
	  cast(a.upto_date as varchar(10)) upto_date, cast(a.reg_Date as varchar(10))  reg_date, a.customer_po_no, cast(a.approval_date as varchar(10)) as  approval_date, a.approval_person_code,
	  0 as sts_design,
	  case when a.approval_date is null and a.approval_person_code is null then '0' else '1' end sts,
	  cast(a.prf_date as varchar(10)) as prfdate from prf_header a
  		$where order by a.prf_date desc";
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