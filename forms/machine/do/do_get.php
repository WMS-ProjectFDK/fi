<?php
	session_start();
	include("../../../connect/conn.php");

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
		$where = "where a.slip_no like '%$src%' ";
	}else{
		$where = "where $date $slip_no $slip_type $item_no $sts_appr a.slip_no is not null ";
	}

	$sql = "
		select top 150  a.slip_no, cast(a.slip_date as varchar(10)) as slip_date, cast(a.slip_date as varchar(10)) as slip_dt, 
		a.slip_type, a.company_code, b.company, a.cost_process_code, c.cost_process_name, cs.cp_section_name,
		a.person_code + ' - ' + upper(p.person) as person_code, 
		cast(a.approval_date as varchar(10)) approval_date 
	    from sp_mte_header a 
		left join sp_company b on a.company_code= b.company_code
		left join person p on a.person_code = p.person_code
		left join SP_COSTPROCESS c on a.cost_process_code=c.cost_process_code
		left join SP_COSTPROCESS_SECTION cs on c.cp_section_code = cs.cp_section_code
		$where 
		order by a.slip_date DESC, a.slip_no desc";
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;

	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		
		if($items[$rowno]->APPROVAL_DATE=='' OR is_null($items[$rowno]->APPROVAL_DATE)){
			$items[$rowno]->STS = 0;
			$items[$rowno]->STS_NAME = '<span style="color:red;font-size:11px;"><b>NOT APPROVE</b></span>';
		}else{
			$items[$rowno]->STS = 1;
			$items[$rowno]->STS_NAME = '<span style="color:blue;font-size:11px;"><b>APPROVE</b></span>';
		}
		
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>