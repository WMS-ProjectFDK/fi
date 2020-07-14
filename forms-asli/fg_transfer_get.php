<?php
	session_start();
	include("../connect/conn.php");

	$sql = "select * from (
		SELECT TRANS_CODE, TRANS_DATE, WO_NO_TEMP, QTY_TEMP, WO_NO, QTY 
		FROM ZTB_FG_TRANSFER
		ORDER BY TRANS_CODE DESC
		) where rownum <=5";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$Q_TEMP = $items[$rowno]->QTY_TEMP;
		$items[$rowno]->QTY_TEMP = number_format($Q_TEMP);
		$Q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($Q);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>