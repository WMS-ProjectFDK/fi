<?php
	session_start();
	$result = array();
	$ym=date('Ym');

	include("../connect/conn2.php");

	$rowno=0;
	$rs = "select a.*,(select count(*) from ztb_prf_quotation_detail_comp where quotation_no=a.quotation_no) as j from ztb_prf_quotation_header a order by a.quotation_no desc";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();

	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$u_entry = $items[$rowno]->USER_ENTRY;
		$u_appr = $items[$rowno]->USER_APPROVAL;

		include("../connect/conn.php");
		$cek_entry = "select person from person where person_code='$u_entry'";
		$dt_entry = oci_parse($connect, $cek_entry);
		oci_execute($dt_entry);
		$dtEntry = oci_fetch_array($dt_entry);
		$items[$rowno]->USER_ENTRY = $dtEntry[0];

		$cek_appr = "select person from person where person_code='$u_appr'";
		$dt_appr = oci_parse($connect, $cek_appr);
		oci_execute($dt_appr);
		$dtAppr = oci_fetch_array($dt_appr);
		$items[$rowno]->USER_APPROVAL = $dtAppr[0];

		$appr = $items[$rowno]->QUOTATION_APPROVED;
		if($appr=='' or is_null($appr)){
			$items[$rowno]->QUOTATION_APPROVED = '<span style="color:red;font-size:11px;"><b>NOT APPROVED</b></span>';
			$items[$rowno]->APPROVED = '0';
		}else{
			$items[$rowno]->QUOTATION_APPROVED = '<span style="color:blue;font-size:11px;"><b>APPROVED</b></span>';
			$items[$rowno]->APPROVED = '1';
		}

		$jum_v = intval($items[$rowno]->J);
		if($jum_v>0){
			$items[$rowno]->STATUS_VENDOR = '<span style="color:blue;font-size:11px;"><b>Y</b></span>';
			$items[$rowno]->J = 'Y';
		}else{
			$items[$rowno]->STATUS_VENDOR = '<span style="color:red;font-size:11px;"><b>N</b></span>';
			$items[$rowno]->J = 'N';
		}
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>