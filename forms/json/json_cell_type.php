<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$sql = "select CELL_TYPE_CODE as id, CELL_TYPE_COMMENT as name from CELL_TYPE 
        where CELL_TYPE_COMMENT is not null
        order by CELL_TYPE_CODE asc";
	$result = sqlsrv_query($connect, $sql);
	$arrNo = 0;
	$arrData = array();
	while ($row=sqlsrv_fetch_object($result)){
		$arrData[$arrNo] = array(
			"NAME"=>rtrim($row->name)
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>