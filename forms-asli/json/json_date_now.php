<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$assy_line = isset($_REQUEST['assy_line']) ? strval($_REQUEST['assy_line']) : '';

	$sql = "select count(assy_line) as jum, to_char(sysdate,'yyyy-mm-dd HH24:MI:SS') as dt from ztb_assy_kanban 
			where replace(assy_line,'#' ,'-') = '$assy_line' AND (end_date is null OR end_date='')";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrNo = 0;
	$arrData = array();
	
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"kode"=>rtrim($row[1]),
			"jum"=>$row[0]
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>