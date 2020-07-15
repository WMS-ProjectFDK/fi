<?php
	session_start();
	header("Content-type: application/json");

	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$cmb_prf_no = isset($_REQUEST['cmb_prf_no']) ? strval($_REQUEST['cmb_prf_no']) : '';
	$ck_prf = isset($_REQUEST['ck_prf']) ? strval($_REQUEST['ck_prf']) : '';
	$srce = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';
	$src = trim($srce);

	if ($ck_date != "true"){
		$prf_date = "a.prf_date between '$date_awal' and '$date_akhir' and ";
	}else{
		$prf_date = "";
	}

	if ($ck_item_no != "true"){
		$item_no = " exists(select * from PRF_DETAILS where item_no = $cmb_item_no and prf_no = a.PRF_NO)  and ";
	}else{
		$item_no = "";
	}

	if ($ck_prf != "true"){
		$prf = "a.prf_no = '".trim($cmb_prf_no)."' and ";
	}else{
		$prf = "";
	}	

	if ($src !='') {
		$where ="where a.prf_no like '%$src%' and a.section_code=100";
	}else{
		$where ="where $prf_date $item_no $prf a.section_code=100 ";
	}
	
	include("../../connect/conn.php");

	#PRF 
  	$sql  = " 
  		select  top 150 a.prf_no, cast(a.prf_date as varchar(10)) as prf_date, a.section_code, replace(a.remark,char(10),'<br>') as remark, a.require_person_code,
  		cast(a.upto_date as varchar(10)) upto_date, cast(a.reg_Date as varchar(10))  reg_date, a.customer_po_no, cast(a.approval_date as varchar(10)) as  approval_date, a.approval_person_code,
  		0 as sts_design,
  		case when a.approval_date is null and a.approval_person_code is null then '0' else '1' end sts,
		  cast(a.prf_date as varchar(10)) as prfdate, isnull(pod.n,0) as jum_po
  		from prf_header a
  		left join (select prf_no, count(prf_no) as n from po_details group by prf_no) pod on a.prf_no = pod.prf_no
  		$where order by prf_date desc, prf_no desc
		   "; 
	$data = sqlsrv_query($connect, strtoupper($sql));
	

	$items = array();
	$rowno=0;
	$FromMRP=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);

		if ($items[$rowno]->sts == '0'){
		

			

			if (is_null($items[$rowno]->CUSTOMER_PO_NO)) {
				$items[$rowno]->status = '<span style="color:red;font-size:11px;"><b>NOT APPROVED</b></span>';
			}else {
				$items[$rowno]->status = '<span style="color:red;font-size:11px;"><b>FROM MRP</b></span>';
			}
		}else{
		

			if (is_null($items[$rowno]->CUSTOMER_PO_NO)) {
				$items[$rowno]->STATUS = '<span style="color:blue;font-size:11px;"><b>APPROVED</b></span>';
			}else {
				$items[$rowno]->STATUS = '<span style="color:blue;font-size:11px;"><b>FROM MRP</b></span>';
			}
		}

		if ($items[$rowno]->JUM_PO == '0'){
			$items[$rowno]->JUMLAH_PO = '<span style="color:red;font-size:11px;"><b>Not Order Processing</b></span>';
		}else{
			$items[$rowno]->JUMLAH_PO = '<span style="color:blue;font-size:11px;"><b>Order Processing</b></span>';
		}

		$prf = $items[$rowno]->PRF_NO;
		$dsign= "select distinct status from ztb_prf_sts where prf_no='$prf'";
		$d_s = sqlsrv_query($connect, strtoupper($dsign));
		
		$row_d = sqlsrv_fetch_object($d_s);
		
		if(intval($row_d->STATUS) == 0 || $row_d->STATUS == ''){
			$items[$rowno]->STS_DSIGN = '-';
			$items[$rowno]->STS_DESIGN = '0';
		}else{
			$items[$rowno]->STS_DSIGN = '<span style="color:blue;font-size:11px;"><b>NEW DESIGN</b></span>';
			$items[$rowno]->STS_DESIGN = '1';
		}

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>