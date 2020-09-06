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
		$where = "where a.slip_no like '%$src%' ";
	}else{
		$where = "where $date $slip_no $slip_type $item_no $sts_appr a.slip_no is not null ";
	}

	$sql = "
		select top 150  a.slip_no, cast(a.slip_date as varchar(10)) as slip_date, cast(a.slip_date as varchar(10)) as slip_dt, 
		a.slip_type, a.company_code, b.company, 
		case when a.person_code = 'KANBAN' then a.person_code 
		when a.person_code is null then ''
		else a.person_code + ' - ' + upper(p.person) end as person_code, 
	    cast(a.approval_date as varchar(10)) approval_date 
	    from mte_header a 
		left join company b on a.company_code= b.company_code
		left join person p on a.person_code = p.person_code
		$where 
		order by a.slip_date DESC";
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

		if(substr($items[$rowno]->SLIP_NO,0,3) == 'RMT' OR substr($items[$rowno]->SLIP_NO,0,2) == 'MT'){
			$items[$rowno]->STS_EDIT = 0;
		}else{
			$items[$rowno]->STS_EDIT = 1;
		}
		
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);

	//session_start();
	//$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	//$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	//$cmb_slip_no = isset($_REQUEST['cmb_slip_no']) ? strval($_REQUEST['cmb_slip_no']) : '';
	//$ck_slip_no = isset($_REQUEST['ck_slip_no']) ? strval($_REQUEST['ck_slip_no']) : '';
	//$cmb_slip_type = isset($_REQUEST['cmb_slip_type']) ? strval($_REQUEST['cmb_slip_type']) : '';
	//$ck_slip_type = isset($_REQUEST['ck_slip_type']) ? strval($_REQUEST['ck_slip_type']) : '';
	//$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	//$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	//$txt_item_name = isset($_REQUEST['txt_item_name']) ? strval($_REQUEST['txt_item_name']) : '';
	//$ck_sts_approval = isset($_REQUEST['ck_sts_approval']) ? strval($_REQUEST['ck_sts_approval']) : '';
	//$cmb_sts_approval = isset($_REQUEST['cmb_sts_approval']) ? strval($_REQUEST['cmb_sts_approval']) : '';
	//$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';
//
//
	//if ($ck_slip_no != "true"){
	//	$slip_no = "a.slip_no = '$cmb_slip_no' and ";
	//}else{
	//	 $slip_no = "";
	//}
//
	//if ($ck_slip_type != "true"){
	//	$slip_type = "a.slip_type = '$cmb_slip_type' and ";
	//}else{
	//	 $slip_type = "";
	//}
//
	//if ($ck_item_no != "true"){
	//	$item_no = "c.item_no='$cmb_item_no' and ";
	//}else{
	//	 $item_no = "";
	//}
//
	//if ($ck_sts_approval != "true"){
	//	if($cmb_sts_approval=='0'){
	//		$sts_appr = "a.approval_date is null and ";
	//	}elseif($cmb_sts_approval=='1'){
	//		$sts_appr = "a.approval_date is not null and ";
	//	}
	//}else{
	//	 $item_name = "";
	//}
//
	//if ($src != '') {
	//	$where = "where a.slip_no like '%$src%' ";
	//}else{
	//	$where = "where $slip_no $slip_type $item_no $sts_appr to_char(a.slip_date,'YYYY-MM-DD') between '$date_awal' and '$date_akhir' ";
	//		//and d.stock_subject_code =1 and d.item_no not between 1200000 and 1299999
	//}
	//
	//include("../connect/conn.php");
//
	//$sql = "select * from (
	//	select distinct a.slip_no, a.slip_date, to_char(a.slip_date,'yyyy-mm-dd') as slip_dt, 
	//	a.slip_type, a.company_code, b.company, a.person_code, a.approval_date from mte_header a 
	//	left join company b on a.company_code= b.company_code
	//	left join mte_details c on a.slip_no=c.slip_no
	//	left join item d on c.item_no=d.item_no
	//	$where order by a.slip_no DESC) where rownum <= 150 ";
	//$data = oci_parse($connect, $sql);
	//oci_execute($data);
//
	//$items = array();
	//$rowno=0;
	//while($row = oci_fetch_object($data)){
	//	array_push($items, $row);
	//	
	//	if($items[$rowno]->APPROVAL_DATE=='' OR is_null($items[$rowno]->APPROVAL_DATE)){
	//		$items[$rowno]->STS = 0;
	//		$items[$rowno]->STS_NAME = '<span style="color:red;font-size:11px;"><b>NOT APPROVE</b></span>';
	//	}else{
	//		$items[$rowno]->STS = 1;
	//		$items[$rowno]->STS_NAME = '<span style="color:blue;font-size:11px;"><b>APPROVE</b></span>';
	//	}
//
	//	if(substr($items[$rowno]->SLIP_NO,0,3) == 'RMT' OR substr($items[$rowno]->SLIP_NO,0,2) == 'MT'){
	//		$items[$rowno]->STS_EDIT = 0;
	//	}else{
	//		$items[$rowno]->STS_EDIT = 1;
	//	}
	//	
	//	$rowno++;
	//}
	//$result["rows"] = $items;
	//echo json_encode($result);
?>