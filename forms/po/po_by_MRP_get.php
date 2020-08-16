<?php
	session_start();
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_prf_date = isset($_REQUEST['ck_prf_date']) ? strval($_REQUEST['ck_prf_date']) : '';
	$cmb_prf_no = isset($_REQUEST['cmb_prf_no']) ? strval($_REQUEST['cmb_prf_no']) : '';
	$ck_prf = isset($_REQUEST['ck_prf']) ? strval($_REQUEST['ck_prf']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$cmb_supp = isset($_REQUEST['cmb_supp']) ? strval($_REQUEST['cmb_supp']) : '';

	if ($ck_prf_date != "true"){
		$date = "a.prf_date between '$date_awal' and '$date_akhir' AND ";
	}else{
		$date = "";
	}

	if ($ck_prf != "true"){
		$prf = "a.prf_no = '$cmb_prf_no' and ";
	}else{
		$prf = "";
	}

	if ($ck_item_no != "true"){
		$item_no = "b.item_no='$cmb_item_no' and ";
	}else{
		$item_no = "";
	}

	if($cmb_supp!=''){
		$supp = "(b.SUPPLIER_CODE = '$cmb_supp' OR c.SUPPLIER_CODE = '$cmb_supp') and ";
	}else{
		$supp = "";
	}
	
	$where ="where $date $prf $item_no $supp a.customer_po_no='MRP' and b.remainder_qty !=0
		and b.DELETE_DATE is null
		and (c.ITEM_NO is null or c.ALTER_PROCEDURE = (select min(ALTER_PROCEDURE) from ITEMMAKER where ITEM_NO = it.ITEM_NO ))
		and a.prf_no + '-' + cast(b.line_no as varchar(5)) not in (select DISTINCT pod.prf_no+'-'+ cast(pod.prf_line_no as varchar(5)) from po_details pod 
							 inner join prf_header ph on pod.prf_no=ph.prf_no 
							 inner join prf_details pd on ph.prf_no=pd.prf_no
			                 where ph.customer_po_no='MRP' AND pd.remainder_qty=0)";
	
	include("../../connect/conn.php");

	$sql = "select distinct a.prf_no, b.line_no as prf_line_no, cast(a.prf_date as varchar(10)) as prf_date, b.item_no, it.item, it.description, b.uom_q, u.unit, 
		b.qty, b.estimate_price, b.amt, cast(b.require_date as varchar(10)) AS eta_date, b.remainder_qty,
		isnull(b.SUPPLIER_CODE,c.SUPPLIER_CODE) as SUPPLIER_CODE, s.COMPANY as SUPPLIER_NAME,
		isnull(b.CURR_CODE,c.CURR_CODE) as CURR_CODE, v.CURR_MARK, v.CURR_SHORT, cast(b.CONFIRM_DATE as varchar(10)) as CONFIRM_DATE, 
		b.CONFIRM_PERSON_CODE, cast(b.DELETE_DATE as varchar(10)) as DELETE_DATE, b.DELETE_PERSON_CODE, 
		case when b.SUPPLIER_CODE is null then -1 when b.CONFIRM_DATE  is null then 0 else 1 end as CONFIRMED,it.origin_code,cou.country
		from prf_header a
		inner join prf_details b on a.prf_no=b.prf_no
		left join item it on b.item_no=it.item_no
		left join unit u on b.uom_q = u.unit_code
		left join country cou on it.origin_code = cou.country_code
		left join itemmaker c on it.item_no= c.item_no
		left join currency v on c.curr_code = v.curr_code
		left join company s on c.supplier_code = s.company_code
		$where ";
	$data = sqlsrv_query($connect, strtoupper($sql));
	

	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$itn = $items[$rowno]->ITEM_NO;
		$itm = $items[$rowno]->ITEM;
		$desc = $items[$rowno]->DESCRIPTION;

		$items[$rowno]->DESCRIPTION_FULL = $itn.' - '.$itm.'<br/>'.$desc;

		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q);

		$ost = $items[$rowno]->REMAINDER_QTY;
		$items[$rowno]->REMAINDER_QTY = number_format($ost);

		$e = $items[$rowno]->ESTIMATE_PRICE;
		$items[$rowno]->ESTIMATE_PRICE = number_format($e,2);

		$a = $items[$rowno]->AMT;
		$items[$rowno]->AMT = number_format($a);
		
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>