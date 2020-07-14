<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$sql = "select replace(assy_line,'#' ,'-') as assy_line, assy_line as assy_line_2 from ztb_assy_set_pallet
			order by assy_line asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrNo = 0;
	$arrData = array();
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"assy_line"=>rtrim($row[0]),
			"assy_line_2"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>