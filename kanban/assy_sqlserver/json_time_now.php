<?php
	include("../../connect/conn_kanbansys.php");
	header("Content-type: application/json");
	$sql = "select convert(varchar,GETDATE(),120) as dt";
	$result = odbc_exec($connect, $sql);
	$arrNo = 0;
	$arrData = array();
	while ($row=odbc_fetch_object($result)){
		$arrData[$arrNo] = array(
			"kode"=>rtrim($row->dt)
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>