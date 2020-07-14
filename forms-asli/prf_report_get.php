<?php
	session_start();
	header("Content-type: application/json");

	$ck_prf_date = isset($_REQUEST['ck_prf_date']) ? strval($_REQUEST['ck_prf_date']) : '';
	$ck_req_date = isset($_REQUEST['ck_req_date']) ? strval($_REQUEST['ck_req_date']) : '';
	$ck_prf = isset($_REQUEST['ck_prf']) ? strval($_REQUEST['ck_prf']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$ck_person = isset($_REQUEST['ck_person']) ? strval($_REQUEST['ck_person']) : '';
	$date_prf_a = isset($_REQUEST['date_prf_a']) ? strval($_REQUEST['date_prf_a']) : '';
	$date_prf_z = isset($_REQUEST['date_prf_z']) ? strval($_REQUEST['date_prf_z']) : '';
	$date_req_a = isset($_REQUEST['date_req_a']) ? strval($_REQUEST['date_req_a']) : '';
	$date_req_z = isset($_REQUEST['date_req_z']) ? strval($_REQUEST['date_req_z']) : '';
	$cmb_prf_no = isset($_REQUEST['cmb_prf_no']) ? strval($_REQUEST['cmb_prf_no']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$txt_item_name = isset($_REQUEST['txt_item_name']) ? strval($_REQUEST['txt_item_name']) : '';
	$cmb_person = isset($_REQUEST['cmb_person']) ? strval($_REQUEST['cmb_person']) : '';
	$ck_view_end = isset($_REQUEST['ck_view_end']) ? strval($_REQUEST['ck_view_end']) : '';
	$ck_approve = isset($_REQUEST['ck_approve']) ? strval($_REQUEST['ck_approve']) : '';

	if ($ck_date != "true"){
		$prf_date = "prf_date between to_date('$date_prf_a','YYYY-MM-DD') and to_date('$date_prf_z','YYYY-MM-DD') and ";
	}else{
		$prf_date = "";
	}

	if ($ck_req_date != "true"){
		$req_date = "req_date between to_date('$date_prf_a','YYYY-MM-DD') and to_date('$date_prf_z','YYYY-MM-DD') and ";
	}else{
		$req_date = "";
	}

	if ($ck_prf != "true"){
		$prf = "x.prf_no = '".trim($cmb_prf_no)."' and ";
	}else{
		$prf = "";
	}

	if ($ck_item_no != "true"){
		$item_no = "x.item_no = $cmb_item_no and ";
	}else{
		$item_no = "";
	}

	if ($ck_person != "true"){
		$person = "y.require_person_code = '$cmb_person' and ";
	}else{
		$person = "";
	}

	if($ck_view_end != "true"){
		$end = "remainder_qty > 0 and ";
	}else{
		$end = "remainder_qty = 0 and ";
	}

	if($ck_approve != "true"){
		$appr = "(y.approval_date is null OR y.approval_person_code is null) and ";
	}else{
		$appr = "(y.approval_date is not null OR y.approval_person_code is not null) and ";
	}
	
	$where = "where $prf_date $req_date $prf $item_no $person $end $appr x.prf_no is not null";

	include("../connect/conn.php");
  	$sql  = "select 
		x.prf_no, x.line_no, y.prf_date as prf_date, y.customer_po_no, y.require_person_code, y.remark, x.item_no,
		s.section, i.item, i.description, decode(x.qty,1,u.unit,u.unit_pl) uom_q,
		ltrim(to_char(x.estimate_price,'99999999990.000000')) u_price, x.qty, x.require_date as req_date,
		x.ohsas, decode(x.remainder_qty, null, x.qty, x.remainder_qty) as remainder_qty
		from prf_details x
		left join prf_header y on x.prf_no = y.prf_no
		left join person p on y.require_person_code = p.person_code
		left join item i on x.item_no = i.item_no
		left join section s on y.section_code = s.section_code
		left join unit u on x.uom_q = u.unit_code
		$where 
		order by description,item,require_date,prf_no,line_no";
	$data = oci_parse($connect, $sql);
	oci_execute($data);
	$items = array();
	$rowno=0;
	$FromMRP=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);

		$prf_no = $items[$rowno]->PRF_NO;
		$line = $items[$rowno]->LINE_NO;
		$items[$rowno]->PRF_NO = $prf_no.' ('.$line.')';

		$item_no = $items[$rowno]->ITEM_NO;
		$item = $items[$rowno]->ITEM;
		$items[$rowno]->ITEM_NO = $item_no.' - '.$item;

		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = $q.' '.$items[$rowno]->UOM_Q.'/<br>'.$items[$rowno]->REMAINDER_QTY.' '.$items[$rowno]->UOM_Q;

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>