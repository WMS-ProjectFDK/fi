<?php
	session_start();
	include("../../../connect/conn.php");

	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
	$cmb_gr_no = isset($_REQUEST['cmb_gr_no']) ? strval($_REQUEST['cmb_gr_no']) : '';
	$ck_gr_no = isset($_REQUEST['ck_gr_no']) ? strval($_REQUEST['ck_gr_no']) : '';
	$cmb_supp = isset($_REQUEST['cmb_supp']) ? strval($_REQUEST['cmb_supp']) : '';
	$ck_supp = isset($_REQUEST['ck_supp']) ? strval($_REQUEST['ck_supp']) : '';
	$cmb_po = isset($_REQUEST['cmb_po']) ? strval($_REQUEST['cmb_po']) : '';
	$ck_po = isset($_REQUEST['ck_po']) ? strval($_REQUEST['ck_po']) : '';
	$cmb_item = isset($_REQUEST['cmb_item']) ? strval($_REQUEST['cmb_item']) : '';
	$ck_item = isset($_REQUEST['ck_item']) ? strval($_REQUEST['ck_item']) : '';
	$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

	if ($ck_date != "true"){
		$gr_date = "a.gr_date BETWEEN CAST('$date_awal' as date) and CAST('$date_akhir' as date) and ";
	}else{
		$gr_date = "";
	}

	if ($ck_gr_no != "true"){
		$gr = "a.gr_no = '$cmb_gr_no' and ";
	}else{
		$gr = "";
	}

	if ($ck_supp != "true"){
		$supp = "a.supplier_code = '$cmb_supp' and ";
	}else{
		$supp = "";
	}

	if ($ck_po != "true"){
		$po = "a.gr_no in (select distinct gr_no from sp_gr_details where po_no='$cmb_po' and ";
	}else{
		$po = "";
	}

	if ($ck_item != "true"){
		$item = "a.gr_no in (select distinct gr_no from sp_gr_details where item_no='$cmb_item' and ";
	}else{
		$item = "";
	}

	if ($src !='') {
		$where="where a.gr_no like '%$src%' ";
	}else{
		$where ="where $gr_date $gr $supp $po $item a.gr_no is not null";
	}

	$sql = "select top 150 a.gr_no, a.sj_no, a.gr_date as gr_date_a, convert(varchar(10), a.gr_date,120) as gr_date, a.supplier_code, c.company, 
		a.curr_code, CAST(a.ex_rate as decimal(18,5)) as ex_rate, b.curr_short, CAST(a.amt_o as decimal(18,2)) amt_o, 
		CAST(a.amt_l as decimal(18,2)) as amt_l, CAST(a.gr_date as varchar(11)) as gr_date_2, a.bc_doc, a.bc_no, a.inv_no, 
		cast(a.inv_date as varchar(11)) inv_date, convert(varchar(10),a.inv_date,120) as inv_date2, 0 EDIT,PURCHASE_TYPE,GR_TYPE,DO_NO as REMARK
		from sp_gr_header a
		left outer join sp_company c on a.supplier_code = c.company_code
		left outer join  currency b on isnull(c.curr_code,23) = b.curr_code
		$where
		order by a.gr_date desc";
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;

	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$q = $items[$rowno]->AMT_L;
		$items[$rowno]->AMT_L = number_format($q,2);
		$q = $items[$rowno]->AMT_O;
		$items[$rowno]->AMT_O = number_format($q,2);
		$rowno++;
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
?>