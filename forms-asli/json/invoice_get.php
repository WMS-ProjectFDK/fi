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

	if ($src !='') {
		$where="where h.do_no like '%$src%'";
	}else{
		$where ="where $cust $item_no $do $date_do to_char(h.do_date,'yyyy')>=(select to_char(add_months(sysdate,-36),'YYYY') from dual) AND h.do_type = 1 AND h.bl_date is null";
	}
	
	include("../connect/conn.php");

	$sql = "select * from (select h.customer_code, c.company customer, h.do_no,do_date, cu.curr_mark, h.ship_end_flg, h.bl_date, h.remark,
		to_char(nvl(h.ex_rate,0),'99990.000000')  ex_rate, to_char(h.amt_o,'9,999,999,990.00') amt_o, to_char(h.amt_o * h.ex_rate,'9,999,999,990.00') amt_l 
		from do_header h
		inner join company c on h.customer_code = c.company_code
		inner join currency cu on h.curr_code = cu.curr_code
		$where 
		order by h.customer_code,h.do_date desc) where rownum <=150";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$cst = $items[$rowno]->CUSTOMER_CODE;
		$cst_name = $items[$rowno]->CUSTOMER;
		$items[$rowno]->CUSTOMER_NAME = '['.$cst.'] '.$cst_name;
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>