<?php
	session_start();
	include("../connect/conn.php");
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
	$cmb_ppbe = isset($_REQUEST['cmb_ppbe']) ? strval($_REQUEST['cmb_ppbe']) : '';
	$ck_ppbe_no = isset($_REQUEST['ck_ppbe_no']) ? strval($_REQUEST['ck_ppbe_no']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';

	if ($ck_date != "true"){
		$date = "to_char(a.cr_date,'YYYY-MM-DD') between '$date_awal' and '$date_akhir' and ";
	}else{
		$date = "";
	}

	if ($ck_ppbe_no != "true"){
		$ppbe = "a.crs_remark = '$cmb_ppbe' and ";
	}else{
		 $ppbe = "";
	}

	if ($ck_item_no != "true"){
		$item_no = "a.crs_remark in (select crs_remark from answer where item_no = '$cmb_item_no') and ";
	}else{
		$item_no = "";
	}
	
	$where ="where $date $ppbe $item_no a.answer_no in (select answer_no from indication 
				where inv_no is null and inv_line_no is null and remark = '1' and commit_date is null) 
			and a.crs_remark is not null and a.si_no is not null";

	$sql = "select a.customer_code, b.company, a.etd, a.eta, a.vessel, a.crs_remark, a.si_no, sum(a.qty) qty, ppbe.jum,
		sih.final_dest, sih.forwarder_name, sih.consignee_name
		from answer a
		inner join company b on a.customer_code = b.company_code
		inner join si_header sih on a.si_no = sih.si_no
		left outer join (select crs_remark, count(*) jum from answer group by crs_remark) ppbe on a.crs_remark=ppbe.crs_remark
		$where 
		group by a.customer_code, b.company, a.cr_date, a.etd, a.eta, a.vessel, a.crs_remark, a.si_no, ppbe.jum,
		sih.final_dest, sih.forwarder_name, sih.consignee_name
		order by a.cr_date desc";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$items[$rowno]->QTY = number_format($items[$rowno]->QTY);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>