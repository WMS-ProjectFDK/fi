<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct a.item_no,b.description from ztb_wh_in_det a left join item b on a.item_no=b.item_no where a.qty - a.qty_out <> 0 order by b.description asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrNo = 0;
	$arrData = array();

	$item = array("ALL","ALL_WHCC","ALL_WHRM","ALL_WHSP","ALL_WHFM","ALL_WHNP","ALL_WHCR");
	$desc = array("ALL ITEM","ALL ITEM IN WH-CC","ALL ITEM IN WH-RM","ALL ITEM IN WH-SEPARATOR","ALL ITEM IN WH-FLAMMABLE","ALL ITEM IN WH-NPS","ALL ITEM IN WH-CORIDOR");

	for ($i=0; $i <= 6 ; $i++) { 
		$arrData[$i] = array("item_no"=>$item[$i],"description"=>$desc[$i] );
		$arrNo++;
	}

	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"item_no"=>rtrim($row[0]), 
			"description"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>