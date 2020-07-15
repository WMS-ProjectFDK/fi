<?php
	session_start();
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
	$supplier = isset($_REQUEST['supplier']) ? strval($_REQUEST['supplier']) : '';
	$ck_supplier = isset($_REQUEST['ck_supplier']) ? strval($_REQUEST['ck_supplier']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$cmb_po = isset($_REQUEST['cmb_po']) ? strval($_REQUEST['cmb_po']) : '';
	$ck_po = isset($_REQUEST['ck_po']) ? strval($_REQUEST['ck_po']) : '';
	$date_eta = isset($_REQUEST['date_eta']) ? strval($_REQUEST['date_eta']) : '';
	$ck_eta = isset($_REQUEST['ck_eta']) ? strval($_REQUEST['ck_eta']) : '';
	$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

	if ($ck_date != "true"){
		$date_po = "a.po_date between '$date_awal' and '$date_akhir' and ";
	}else{
		$date_po = "";
	}	

	if ($ck_supplier != "true"){
		$supp = "a.supplier_code = '$supplier' and ";
	}else{
		$supp = "";
	}

	if ($ck_item_no != "true"){
		$item_no = "a.po_no in (select po_no from po_details where item_no = '$cmb_item_no') and ";
	}else{
		$item_no = "";
	}

	if ($ck_po != "true"){
		$po = "a.po_no='$cmb_po' and ";
	}else{
		$po = "";
	}	

	if ($ck_eta != "true"){
		$eta = "a.po_no in (select po_no from po_details where ETA = '$date_eta' and ";
	}else{
		$eta = "";
	}

	if ($src !='') {
		$where="where (a.po_no like '%$src%')";
	}else{
		$where ="where $supp $item_no $po $eta $date_po b.delete_type is NULL";
	}
	
	include("../../connect/conn.php");

	$sql = "select top 150 a.PO_NO, cast(a.po_date as varchar(10)) as po_date, a.supplier_code, b.company, c.curr_short, c.curr_mark, a.amt_o, a.amt_l,
		a.remark1,a.req, prsn.person,replace(remark1,'char(10)','<br/>') as remark1_2, cast(b.pdays as varchar(10))+'-'+b.pdesc as pterm, 
		cast(b.country_code as varchar(20))+'-'+cnt.country as country, a.curr_code, a.attn, a.shipto_code,a.tterm, 
		a.ex_rate, a.transport, a.marks1, a.di_output_type, a.revise, a.reason1
		from po_header a
		left join company b on a.supplier_code=b.company_code
		left join currency c on a.curr_code= c.curr_code
    	left join country cnt on b.country_code=cnt.country_code
    	left join person prsn on a.req=prsn.person_code
		$where 
		order by po_date desc";
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$items[$rowno]->O = number_format($items[$rowno]->AMT_O,2);
		$items[$rowno]->L = $items[$rowno]->CURR_MARK." ".number_format($items[$rowno]->AMT_L,2);
		$items[$rowno]->REQ_2 =strtoupper($items[$rowno]->PERSON);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);

	
?>