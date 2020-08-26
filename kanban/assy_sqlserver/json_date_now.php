<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$assy_line = isset($_REQUEST['assy_line']) ? strval($_REQUEST['assy_line']) : '';

	$sql = "select count(assy_line) as jum, convert(varchar,GETDATE(),120) as dt from ztb_assy_kanban 
			where replace(assy_line,'#' ,'-') = '$assy_line' AND (end_date is null OR end_date='')";
	$result = sqlsrv_query($connect, $sql);
	$arrNo = 0;
	$arrData = array();
	
	while ($row=sqlsrv_fetch_object($result)){
		$arrData[$arrNo] = array(
			"kode"=>rtrim($row->dt),
			"jum"=>$row->jum
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>