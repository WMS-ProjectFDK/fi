<?php
	session_start();
	include("../connect/conn.php");

	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$cmb_prf_no = isset($_REQUEST['cmb_prf_no']) ? strval($_REQUEST['cmb_prf_no']) : '';
	$ck_prf = isset($_REQUEST['ck_prf']) ? strval($_REQUEST['ck_prf']) : '';

	if ($ck_date != "true"){
		$date = "a.prf_date between to_date('$date_awal','YYYY-MM-DD') and to_date('$date_akhir','YYYY-MM-DD')  and ";
	}else{
		$date = "";
	}

	if ($ck_item_no != "true"){
		$item_no = "b.item_no = $cmb_item_no and ";
	}else{
		$item_no = "";
	}

	if ($ck_prf != "true"){
		$prf = "a.prf_no = '".trim($cmb_prf_no)."' and ";
	}else{
		$prf = "";
	}	

	if ($src !='') {
		$where="";
	}else{
		$where ="where $item_no $prf $date a.section_code=100 and approval_date is null and approval_person_code is null";
	}

  	$sql  = "select * from (select distinct a.* from prf_header a
  		inner join prf_details b on a.prf_no = b.prf_no
  		$where order by a.prf_date desc) where rownum <=150";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>