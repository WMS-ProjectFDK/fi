<?php
	ini_set('max_execution_time', -1);
	session_start();
	$PPBE = isset($_REQUEST['PPBE']) ? strval($_REQUEST['PPBE']) : '';
	$WO = isset($_REQUEST['WO']) ? strval($_REQUEST['WO']) : '';

    include("../../connect/conn.php");
    
	$sql = "select PPBE_NO,'$WO' WO_NO,ITEM_NO, QTY,PALLET,CARTON_NOT_FULL,CONTAINERS,NET,GROSS,MSM,
		ROW_NUMBER() over (order by answer_no asc) RD,
		CONTAINER_VALUE  from ztb_shipping_detail where ppbe_no = '$PPBE' and wo_no = '$WO' ";
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>