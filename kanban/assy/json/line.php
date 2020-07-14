<?php
	header("Content-type: application/json");
	include("../../../connect/conn_kanbansys.php");
	$sql = "select distinct line from assembly_line_master";
	$result = odbc_exec($connect, $sql);
	$arrNo = 0;
	$arrData = array();
	while ($row=odbc_fetch_object($result)){
		$arrData[$arrNo] = array(
			"line"=>$row->line
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>