<?php
	ini_set('max_execution_time', -1);
	session_start();
	$PPBE = isset($_REQUEST['PPBE']) ? strval($_REQUEST['PPBE']) : '';
	
	$WO = isset($_REQUEST['WO']) ? strval($_REQUEST['WO']) : '';
	

	include("../connect/conn.php");

	

	$sql = "select PPBE_NO,'$WO' WO_NO,ITEM_NO, QTY,PALLET,CARTON_NOT_FULL,CONTAINERS,NET,GROSS,MSM,cast(ROWID as varchar(50)) RD,CONTAINER_VALUE  from ztb_shipping_detail where ppbe_no = '$PPBE' and wo_no = '$WO'";

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