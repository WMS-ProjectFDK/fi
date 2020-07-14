<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$sql = "select distinct to_number(id_1) as id, 'MT-'||id_1 as mt_no
		from ztb_wh_out_det
		where id_1 is not null
		order by to_number(id_1) desc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrNo = 0;
	$arrData = array();
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"id"=>rtrim($row[0]),
			"mt_no"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>