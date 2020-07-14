<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select to_char(sysdate,'dd-MON-yyyy HH24:MI:SS') as dt from dual";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrNo = 0;
	$arrData = array();
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"kode"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>