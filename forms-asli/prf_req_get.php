<?php
	session_start();
	$result = array();
	$ym=date('Ym');

	include("../connect/conn2.php");

	$rowno=0;
	$rs = "select distinct a.req_no, a.req_date,TO_CHAR(a.req_date,'yyyy-mm-dd') as req_dt, a.total, a.user_entry, a.last_update, a.type_budget, a.remarks,a.type_complete from ztb_prf_req_header a inner join ztb_prf_req_details b on a.req_no=b.req_no where b.sts_approval='0' and b.sts_PO='0' and TO_CHAR(req_date,'yyyymm')= '$ym' order by a.req_no desc";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();

	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$u_entry = $items[$rowno]->USER_ENTRY;
		
		include("../connect/conn.php");
		$cek_entry = "select person from person where person_code='$u_entry'";
		$dt_entry = oci_parse($connect, $cek_entry);
		oci_execute($dt_entry);
		$dtEntry = oci_fetch_array($dt_entry);
		$items[$rowno]->USER_ENTRY = $dtEntry[0];

		$t = $items[$rowno]->TOTAL;
		$items[$rowno]->TOTAL = number_format($t);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>