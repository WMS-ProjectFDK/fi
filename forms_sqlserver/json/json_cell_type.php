<?php
	include("../../connect/conn_kanbansys.php");
	header("Content-type: application/json");

	$sql = "select id, name from CELL_TYPE order by id asc";
	$result = odbc_exec($connect, $sql);
	$arrNo = 0;
	$arrData = array();
	while ($row=odbc_fetch_object($result)){
		$arrData[$arrNo] = array(
			"NAME"=>rtrim($row->name)
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>