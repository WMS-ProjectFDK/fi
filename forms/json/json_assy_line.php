<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$sql = "select id, name, replace(name,'#' ,'-') as name2 from assy_line 
		where name not in ('LR06#5')
		order by id asc";
	$result = sqlsrv_query($connect, $sql);
	$arrNo = 0;
	$arrData = array();
	while ($row=sqlsrv_fetch_object($result)){
		$arrData[$arrNo] = array(
			"NAME"=>rtrim($row->name),
			"NAME2"=>rtrim($row->name2)
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>