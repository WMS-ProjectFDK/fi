<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct a.require_person_code, b.person from prf_header a
		inner join person b on a.require_person_code=b.person_code
		where b.person is not null";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"id_person"=>rtrim($row[0]), 
			"person"=>rtrim($row[1]),
			"id_name_person"=>rtrim($row[0])." - ".strtoupper(rtrim($row[1]))
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>