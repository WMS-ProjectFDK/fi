<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$yearmin = intval(date('Y'))-1;
	$year = date('Y');
	$yearplus = intval(date('Y')+1);
	//echo $yearmin.$year.$yaerplus;
	$sql = " select distinct work_order ,po_no,item_no || ' - ' || item_name Item_no  from mps_header   ";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrNo = 0;
	$arrData = array();
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"work_order"=>rtrim($row[0]),
			"po_no"=>rtrim($row[1]),
			"item_no"=>rtrim($row[2])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>