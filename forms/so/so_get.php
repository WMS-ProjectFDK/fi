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
	$cmb_so_no = isset($_REQUEST['cmb_so_no']) ? strval($_REQUEST['cmb_so_no']) : '';
	$ck_so_no = isset($_REQUEST['ck_so_no']) ? strval($_REQUEST['ck_so_no']) : '';
	$cmb_cust = isset($_REQUEST['cmb_cust']) ? strval($_REQUEST['cmb_cust']) : '';
	$ck_cust = isset($_REQUEST['ck_cust']) ? strval($_REQUEST['ck_cust']) : '';
	$cmb_cust_po = isset($_REQUEST['cmb_cust_po']) ? strval($_REQUEST['cmb_cust_po']) : '';
	$ck_cust_po = isset($_REQUEST['ck_cust_po']) ? strval($_REQUEST['ck_cust_po']) : '';
	$cmb_item = isset($_REQUEST['cmb_item']) ? strval($_REQUEST['cmb_item']) : '';
	$ck_item = isset($_REQUEST['ck_item']) ? strval($_REQUEST['ck_item']) : '';
	$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

	if ($ck_date != "true"){
		$so_date = "a.so_date BETWEEN CAST('$date_awal' as date) and CAST('$date_akhir' as date) and ";
	}else{
		$so_date = "";
	}

	if ($ck_so_no != "true"){
		$so = "a.so_no = '$cmb_so_no' and ";
	}else{
		$so = "";
	}

	if ($ck_cust != "true"){
		$cust = "a.customer_code = '$cmb_cust' and ";
	}else{
		$cust = "";
	}

	if ($ck_cust_po != "true"){
		$po = "a.customer_po_no='$cmb_cust_po' and ";
	}else{
		$po = "";
	}

	if ($ck_item != "true"){
		$item = "a.so_no in (select so_no from SO_DETAILS where item_no=$cmb_item)";// gd.item_no='$cmb_item' and ";
	}else{
		$item = "";
	}

	if ($src !='') {
		$where="where a.so_no like '%$src%' ";
	}else{
		$where ="where $so_date $so $cust $po $item a.so_no is not null";
	}

    $sql = "select distinct top 150 a.so_no, cast(a.so_date as varchar(10)) as so_date, a.CUSTOMER_PO_NO,
        a.customer_code, c.COMPANY, a.CURR_CODE, cast(a.ex_rate as decimal(18,5)) as ex_rate,
        d.CURR_SHORT, a.REMARK, a.AMT_O, a.AMT_L, e.PERSON, a.CONSIGNEE_CODE
        from SO_HEADER a
        inner join so_details b on a.so_no=b.so_no
        left join COMPANY c on a.CUSTOMER_CODE = c.COMPANY_CODE
        left join currency d on a.CURR_CODE = d.CURR_CODE 
        left join person e on a.PERSON_CODE = e.PERSON_CODE
        $where
        order by cast(a.so_date as varchar(10)) desc";
	$data = sqlsrv_query($connect, strtoupper($sql));
    // echo $sql;
	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>