<?php
	session_start();
	include("../../connect/conn.php");

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
		$po = "gd.po_no='$cmb_po' and ";
	}else{
		$po = "";
	}

	if ($ck_item != "true"){
		$item = "gd.item_no='$cmb_item' and ";
	}else{
		$item = "";
	}

	if ($src !='') {
		$where="where a.gr_no like '%$src%' ";
	}else{
		$where ="where $gr_date $gr $supp $po $item a.gr_no is not null";
	}

	$sql = "select distinct top 150 a.gr_no, CAST(a.gr_date as varchar(10)) as gr_date, a.supplier_code, c.company, a.curr_code, CAST(a.ex_rate as decimal(18,5)) as ex_rate,
		a.pdays, a.pdesc, b.curr_short, a.remark, CAST(a.amt_o  as decimal(18,2))  amt_o, CAST(a.amt_l  as decimal(18,2)) as amt_l, CAST(a.gr_date as varchar(10)) as gr_date_2, a.bc_doc, a.bc_no
		,a.inv_no,cast(a.inv_date as varchar(10)) inv_date,0 EDIT
		from gr_header a
		inner join gr_details gd on a.gr_no=gd.gr_no
		inner join currency b on a.curr_code = b.curr_code
		inner join company c on a.supplier_code = c.company_code
		$where
		order by CAST(a.gr_date as varchar(10)) desc";
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$terms1 = $items[$rowno]->PDAYS;
		$terms2 = $items[$rowno]->PDESC;
		$items[$rowno]->PAYTERMS = $terms1.' '.$terms2;
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>