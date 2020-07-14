<?php
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$idx = isset($_REQUEST['index']) ? strval($_REQUEST['index']) : '';
	include("../../connect/conn2.php");
	header("Content-type: application/json");
	$sql = "select company_code, company from company where company_code in(select distinct vendor from ztb_prf_req_details where sts_approval='1' and vendor is not null and sts_po='0')
		order by company asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"COMPANY_CODE"=>rtrim($row[0]), 
			"COMPANY"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>