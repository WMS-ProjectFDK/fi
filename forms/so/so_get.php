<?php
	session_start();
    include("../../connect/conn.php");
    
    // date_awal=2020-07-27
    // date_akhir=2020-07-27
    // ck_date=true
    // cmb_so_no=
    // ck_so_no=true
    // cmb_cust=
    // ck_cust=true
    // cmb_cust_po=
    // ck_cust_po=true
    // cmb_item=
    // ck_item=true

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

    $sql = "select distinct top 150 a.so_no, cast(a.so_date as varchar(10)) as so_date, 
        a.customer_code, c.COMPANY, a.CURR_CODE, cast(a.ex_rate as decimal(18,5)) as ex_rate,
        d.CURR_SHORT, a.REMARK, a.AMT_O, a.AMT_L, e.PERSON
        from SO_HEADER a
        inner join so_details b on a.so_no=b.so_no
        left join COMPANY c on a.CUSTOMER_CODE = c.COMPANY_CODE
        left join currency d on a.CURR_CODE = d.CURR_CODE 
        left join person e on a.PERSON_CODE = e.PERSON_CODE
        order by cast(a.so_date as varchar(10)) desc";
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>