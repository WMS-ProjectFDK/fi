<?php
	error_reporting(0);
	session_start();
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_cr_date = isset($_REQUEST['ck_cr_date']) ? strval($_REQUEST['ck_cr_date']) : '';
	$cmb_wo_no = isset($_REQUEST['cmb_wo_no']) ? strval($_REQUEST['cmb_wo_no']) : '';
	$ck_wo_no = isset($_REQUEST['ck_wo_no']) ? strval($_REQUEST['ck_wo_no']) : '';
	$cmb_po_no = isset($_REQUEST['cmb_po_no']) ? strval($_REQUEST['cmb_po_no']) : '';
	$ck_po_no = isset($_REQUEST['ck_po_no']) ? strval($_REQUEST['ck_po_no']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$text_item = isset($_REQUEST['text_item']) ? strval($_REQUEST['text_item']) : '';
	$ck_item = isset($_REQUEST['ck_item']) ? strval($_REQUEST['ck_item']) : '';
	$flag = isset($_REQUEST['flag']) ? strval($_REQUEST['flag']) : '';

	if ($ck_cr_date != "true"){
		$date = "cr_Date between '$date_awal' and '$date_akhir' AND ";
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

	if ($ck_item != "true"){
		
		$item = "item_name like '%$text_item%' and ";
	}else{
		$item = "";
	}

	if($ck_po_no!='true'){
		$supp = " po_no  = '$cmb_po_no' and ";
	}else{
		$supp = "";
	}

	
	
	$where ="where $date $prf $item_no $supp $item status = 'FM'";
	
	include("../../connect/conn.php");

	$sql = "select top 150 '1' SHIPPING ,work_order,po_no,po_line_no,cast(cr_date as varchar(10)) as cr_date,batery_type,cell_grade,item_no,item_name,isnull(qty,0) Qty_order,
			isnull(qty_prod,0) Qty_Produksi, isnull(qty_invoiced,0) qty_invoiced,si_no, ans.crdate_ship_plan
			from mps_header mh 
			left outer join (select wo_no,sum(case when slip_type = 80 then slip_quantity else slip_quantity*-1 end) qty_prod 
			from production_income group by wo_no) pi on mh.work_order = pi.wo_no 
			left outer join (select sum(isnull(do_so.qty,0)) qty_invoiced,work_no from answer inner join do_so on do_so.answer_no = answer.answer_no group by work_no)inv on mh.work_order = inv.work_no 
			left outer join (select max(si_no) si_no,cust_si_no from  si_header group by cust_si_no) sh on sh.cust_si_no = mh.po_no 
			left join (select distinct work_no, cast(cr_date as varchar(10)) as crdate_ship_plan from answer) ans on mh.work_order=ans.work_no
			$where
			order by cr_date";
	
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);

		$w = "'".$items[$rowno]->WORK_ORDER."'";
		$q = $items[$rowno]->QTY_ORDER;
		$items[$rowno]->QTY_ORDER = number_format($q);

		$e = $items[$rowno]->QTY_PRODUKSI;
		$items[$rowno]->QTY_PRODUKSI = '<a href="javascript:void(0)" title="'.$e.'" onclick="info_kuraire('.$w.')"  style="text-decoration: none; ">'.number_format($e).'</a>';

		$f = $items[$rowno]->QTY_INVOICED;
		$items[$rowno]->QTY_INVOICED = '<a href="javascript:void(0)" title="'.$f.'" onclick="info_invoiced('.$w.')"  style="text-decoration: none; ">'.number_format($f).'</a>';
		
		$h = $items[$rowno]->ITEM_NO;
		$items[$rowno]->ITEM_NO = '<a href="javascript:void(0)" title="'.$h.'" onclick="info_item('.$h.')"  style="text-decoration: none; ">'.$h.'</a>';
		
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>