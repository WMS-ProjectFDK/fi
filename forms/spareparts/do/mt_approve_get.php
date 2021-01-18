<?php
	include("../../../connect/conn.php");
	session_start();
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
	$cmb_slip_no = isset($_REQUEST['cmb_slip_no']) ? strval($_REQUEST['cmb_slip_no']) : '';
	$ck_slip_no = isset($_REQUEST['ck_slip_no']) ? strval($_REQUEST['ck_slip_no']) : '';
	$cmb_slip_type = isset($_REQUEST['cmb_slip_type']) ? strval($_REQUEST['cmb_slip_type']) : '';
	$ck_slip_type = isset($_REQUEST['ck_slip_type']) ? strval($_REQUEST['ck_slip_type']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';

	if($ck_date != "true"){
		$date = "a.slip_date between '$date_awal' and '$date_akhir' and ";
	}else{
		$date = "";
	}

	if ($ck_slip_no != "true"){
		$slip_no = "a.slip_no = '$cmb_slip_no' and ";
	}else{
		 $slip_no = "";
	}

	if ($ck_slip_type != "true"){
		$slip_type = "a.slip_type = '$cmb_slip_type' and ";
	}else{
		 $slip_type = "";
	}

	if ($ck_item_no != "true"){
		$item_no = " a.slip_no in (select SLIP_NO from mte_details where item_no='$cmb_item_no' and slip_no = a.SLIP_NO)  and ";
	}else{
		 $item_no = "";
	}
	
	$where ="where $slip_no $slip_type $item_no $date
		a.approval_date is null and a.approval_person_code is null
		and (LEFT(CONVERT(varchar, a.slip_date,112),6) = (select top 1 THIS_MONTH from WHINVENTORY) OR
			 LEFT(CONVERT(varchar, a.slip_date,112),6) = (select top 1 LAST_MONTH from WHINVENTORY)) ";
	
	

	$sql = "select  top 150 a.slip_no, cast(a.slip_date as varchar(10)) slip_date, cast(a.slip_date as varchar(10)) as slip_date_a,
		a.cost_process_code, c.COST_PROCESS_NAME, cs.cp_section_name,
		case when a.person_code = 'KANBAN' then a.person_code
		when a.person_code is null then ''
		else cast(a.person_code as varchar(20)) + ' - ' + upper(p.person) end as person_code, 
		a.approval_date, a.slip_type, sl.slip_name,
		isnull((select count(it.item_no) from mte_details it 
			left join whinventory whi on it.item_no=whi.item_no
			left join mte_header mth on it.slip_no=mth.slip_no
			where it.slip_no=a.slip_no and 
				mth.slip_type NOT IN ('20','80','05') and
				it.qty > whi.this_inventory)
		,0) as sts
		from sp_mte_header a 
		left join sp_company b on a.company_code= b.company_code
		left join sliptype sl on a.slip_type= sl.slip_type
		left join person p on a.person_code = p.person_code
		left join SP_COSTPROCESS c on a.cost_process_code = c.COST_PROCESS_CODE 
		left join sp_costprocess_section cs on c.cp_section_code = cs.cp_section_code
		$where
		order by a.slip_date desc, a.slip_no asc
        ";
	$data = sqlsrv_query($connect, strtoupper($sql));


	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$items[$rowno]->SLIP_NM = "[".$items[$rowno]->SLIP_TYPE."] ".$items[$rowno]->SLIP_NAME;

		$sts = $items[$rowno]->STS;
		if($sts==0){
			$items[$rowno]->STS_NM = '<span style="color:BLUE;font-size:11px;"><b>OK</b></span>';
		}else{
			$items[$rowno]->STS_NM = '<span style="color:WHITE;font-size:11px;"><b>OVER</b></span>';
		}
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>