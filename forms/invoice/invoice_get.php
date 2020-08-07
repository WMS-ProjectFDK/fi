<?php
	session_start();
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
	$customer = isset($_REQUEST['customer']) ? strval($_REQUEST['customer']) : '';
	$ck_customer = isset($_REQUEST['ck_customer']) ? strval($_REQUEST['ck_customer']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$cmb_do = isset($_REQUEST['cmb_do']) ? strval($_REQUEST['cmb_do']) : '';
	$ck_do = isset($_REQUEST['ck_do']) ? strval($_REQUEST['ck_do']) : '';
	$cmb_po = isset($_REQUEST['cmb_po']) ? strval($_REQUEST['cmb_po']) : '';
	$ck_po = isset($_REQUEST['ck_po']) ? strval($_REQUEST['ck_po']) : '';
	$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';
	
	if ($ck_date != "true"){
		$date_do = "to_char(h.do_date,'YYYY-MM-DD') between '$date_awal' and '$date_akhir' and ";
	}else{
		$date_do = "";
	}	

	if ($ck_customer != "true"){
		$cust = "h.customer_code = '$customer' and ";
	}else{
		$cust = "";
	}

	if ($ck_item_no != "true"){
		$item_no = "h.do_no in (select do_no from do_details where item_no = '$cmb_item_no') and ";
	}else{
		$item_no = "";
	}

	if ($ck_do != "true"){
		$do = "h.do_no='$cmb_do' and ";
	}else{
		$do = "";
	}	

	if ($ck_po != "true"){
		$po = "h.do_no in (select distinct do_no from do_details where customer_po_no1 = '$cmb_po') and ";
	}else{
		$po = "";
	}	

	if ($src !='') {
		$where="where h.do_no like '%$src%'";
	}else{
		$where ="where $cust $item_no $do $date_do $po to_char(h.do_date,'yyyy')>=(select to_char(add_months(sysdate,-36),'YYYY') from dual) AND h.do_type = 1 AND h.bl_date is null";
	}
	
	include("../../connect/conn.php");

	$sql = "select * from (select h.customer_code, c.company customer, h.do_no,do_date, cu.curr_mark, h.ship_end_flg, h.bl_date, 
		rtrim(replace(h.remark,chr(10),'<br>'),'|') as remark1, h.remark, h.person_code, pe.person,
		to_char(nvl(h.ex_rate,0),'99990.000000')  ex_rate, to_char(h.amt_o,'9,999,999,990.00') amt_o, to_char(h.amt_o * h.ex_rate,'9,999,999,990.00') amt_l,
		h.curr_code, h.trade_term, h.attn, h.pby, h.pdays, h.pdesc, h.gst_rate, c.country_code, co.country, h.si_no, h.contract_seq, substr(h.trade_term, 0, 3) term,
		rtrim(replace(h.ship_name,chr(10),'<br>'),'|') as ship_name, 
		si.load_port_code, si.load_port, si.disch_port_code, h.port_discharge, si.final_dest_code, h.final_destination, 
		rtrim(replace(h.notify,chr(10),'<br>'),'|') as notify,
		to_char(h.eta,'YYYY-MM-DD') as eta, to_char(h.etd, 'YYYY-MM-DD') as etd, f.transport_type, f.forwarder_code, f.domestic_truck_code, f.booking_no,
		f.cargo_type1, f.cargo_size1, f.cargo_qty1, f.cargo_type2, f.cargo_size2, f.cargo_qty2, to_char(h.do_date,'YYYY-MM-DD') as TANGGAL_DO, si.goods_name, ppbe.ppbe_no, idc.sts as delivery_update, idc.rmk
		from do_header h
		inner join company c on h.customer_code = c.company_code
		inner join country co on c.country_code = co.country_code
		inner join currency cu on h.curr_code = cu.curr_code
		left outer join si_header si on h.si_no = si.si_no
		inner join FORWARDER_LETTER f on h.do_no = f.do_no
		left join person pe on h.person_code = pe.person_code
		left join (select distinct si_no, crs_remark as ppbe_no from answer) ppbe on h.si_no = ppbe.si_no and h.description = ppbe.ppbe_no
		left join (select distinct inv_no, case when commit_date is null then 'NOT DELIVERY' else to_char(commit_date, 'DD-MM-YYYY') end sts, max(remark) as rmk
			from indication group by inv_no, commit_date) idc on h.do_no = idc.inv_no
		$where
		order by h.customer_code,h.do_date desc) where rownum <=150";
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$cst = $items[$rowno]->CUSTOMER_CODE;
		$cst_name = $items[$rowno]->CUSTOMER;
		$items[$rowno]->CUSTOMER_NAME = '['.$cst.'] '.$cst_name;
		$person = $items[$rowno]->PERSON;
		$items[$rowno]->PERSON =strtoupper($person);
		$s = $items[$rowno]->DELIVERY_UPDATE; 
		if($s == 'NOT DELIVERY'){
			$items[$rowno]->DELIVERY_UPDATE = '<span style="color:red;font-size:11px;"><b>'.$s.'</b></span>';
			$items[$rowno]->STS = 0;
		}else{
			$items[$rowno]->DELIVERY_UPDATE = '<span style="color:blue;font-size:11px;"><b>'.$s.'</b></span>';
			$items[$rowno]->STS = 1;
		}

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>