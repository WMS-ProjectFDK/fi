<?php
	session_start();
	include("../connect/conn.php");

	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_cr_date = isset($_REQUEST['ck_cr_date']) ? strval($_REQUEST['ck_cr_date']) : '';
	$cmb_wo_no = isset($_REQUEST['cmb_wo_no']) ? strval($_REQUEST['cmb_wo_no']) : '';
	$ck_wo_no = isset($_REQUEST['ck_wo_no']) ? strval($_REQUEST['ck_wo_no']) : '';
	$cmb_po_no = isset($_REQUEST['cmb_po_no']) ? strval($_REQUEST['cmb_po_no']) : '';
	$ck_po_no = isset($_REQUEST['ck_po_no']) ? strval($_REQUEST['ck_po_no']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$cmb_ppbe = isset($_REQUEST['cmb_ppbe']) ? strval($_REQUEST['cmb_ppbe']) : '';
	$ck_ppbe = isset($_REQUEST['ck_ppbe']) ? strval($_REQUEST['ck_ppbe']) : '';
	$flag = isset($_REQUEST['flag']) ? strval($_REQUEST['flag']) : '';
	$ck_si = isset($_REQUEST['ck_si']) ? strval($_REQUEST['ck_si']) : '';
	$cmb_si_no = isset($_REQUEST['cmb_si_no']) ? strval($_REQUEST['cmb_si_no']) : '';

	if ($ck_cr_date != "true"){
		$date = "to_char(cr_date,'yyyy-mm-dd') between '$date_awal' and '$date_akhir' AND ";
	}else{
		$date = "";
	}

	if ($ck_wo_no != "true"){
		$prf = "work_order = '$cmb_wo_no' and ";
	}else{
		$prf = "";
	}

	if ($ck_item_no != "true"){
		$pieces = explode('-', $cmb_item_no);
		$part1 = implode('-', array_slice($pieces, 0, 1));
		$item_no = "item_no='$part1' and ";
	}else{
		$item_no = "";
	}

	if($ck_po_no!='true'){
		$supp = " po_no  = '$cmb_po_no' and ";
	}else{
		$supp = "";
	}

	if($ck_ppbe!='true'){
		$ppbe = " inv.crs_remark  = '$cmb_ppbe' and ";
	}else{
		$ppbe = "";
	}

	if  ($flag == 4){
		$filter = " rownum <= 150 and ";
	}
	
	$where =" $date $prf $item_no $supp $ppbe $filter ";
	
	$sql = "select distinct customer_code, crs_remark, si_no, vessel, remark, 
		to_char(etd,'YYYY-MM-DD') as etd, to_char(eta,'YYYY-MM-DD') as eta, to_char(stuffy_date,'YYYY-MM-DD') as stuffy_date, operation_date, 
		consignee_name, forwarder_name, emkl_name
				from (
				select distinct a.customer_code, a.crs_remark, a.si_no, a.cr_date, a.vessel, a.remark, a.etd, a.eta, a.stuffy_date, 
				to_char(a.operation_date,'YYYY-MM-DD') as operation_date,
		    	b.consignee_name, b.forwarder_name, b.emkl_name
				from answer a
		    	inner join si_header b on a.si_no=b.si_no
				where to_char(a.data_date,'YYYY') > (SELECT to_char(add_months(to_date(sysdate),-12),'YYYY') FROM DUAL)
				and (a.crs_remark is not null or a.si_no is not null)
				)order by operation_date desc, crs_remark desc";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>