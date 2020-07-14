<?php
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
	$flag = isset($_REQUEST['flag']) ? strval($_REQUEST['flag']) : '';

	if ($ck_cr_date != "true"){
		$date = "to_char(cr_date,'yyyy-mm-dd') between '$date_awal' and '$date_akhir' AND ";

	}else{
		$date = "";
	}

	if ($ck_wo_no != "true"){
		$prf = "WO_NO = '$cmb_wo_no' and ";
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

	if  ($flag == 4){
		$filter = " rownum <= 150 and ";
	}
	
	$where =" $date $prf $item_no $supp $filter ";
	
	include("../connect/conn.php");

	$sql = "select * from ztb_shipping_plan where $where  so_no is not null order by cr_date";
	//echo $sql;
	
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);

		// $w = "'".$items[$rowno]->WORK_ORDER."'";
		

		// $q = $items[$rowno]->QTY_ORDER;
		// $items[$rowno]->QTY_ORDER = number_format($q);

		// //$e = $items[$rowno]->QTY_PRODUKSI;
		// //$items[$rowno]->QTY_PRODUKSI = $e;//'<a href="javascript:void(0)" title="'.$e.'"   style="text-decoration: none; color: black;">'number_format($e).'</a>';

		// $e = $items[$rowno]->QTY_PRODUKSI;
		// $items[$rowno]->QTY_PRODUKSI = '<a href="javascript:void(0)" title="'.$e.'" onclick="info_kuraire('.$w.')"  style="text-decoration: none; color: black;">'.number_format($e).'</a>';

		// $f = $items[$rowno]->QTY_INVOICED;
		// $items[$rowno]->QTY_INVOICED = '<a href="javascript:void(0)" title="'.$f.'" onclick="info_invoiced('.$w.')"  style="text-decoration: none; color: black;">'.number_format($f).'</a>';

		// $g = $items[$rowno]->QTY_PLAN;
		// $items[$rowno]->QTY_PLAN = '<a href="javascript:void(0)" title="'.$g.'" onclick="info_plan('.$w.')"  style="text-decoration: none; color: black;">'.number_format($g).'</a>';
		
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>