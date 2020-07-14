<?php
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$idx = isset($_REQUEST['index']) ? strval($_REQUEST['index']) : '';
	include("../../connect/conn2.php");
	header("Content-type: application/json");
	$sql = "select a.supplier_code,b.company,a.estimate_price,c.curr_short from itemmaker a
		left join company b on a.supplier_code=b.company_code left join currency c on a.curr_code=c.curr_code
		where a.item_no='$item' order by b.company asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"INDEX"=>$idx,
			"SUPPLIER_CODE"=>rtrim($row[0]), 
			"COMPANY"=>rtrim($row[1]),
			"ESTIMATE_PRICE"=>number_format(($row[2])),
			"CURR"=>rtrim($row[3])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>