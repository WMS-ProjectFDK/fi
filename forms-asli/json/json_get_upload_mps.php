<?php
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$idx = isset($_REQUEST['index']) ? strval($_REQUEST['index']) : '';
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct to_char(UPLOAD_DATE,'yyyy-mm-dd hh24miss') LASTED,upload_date from mps_header_rireki where upload_date > (select sysdate - 100 from dual ) 
and to_char(UPLOAD_DATE,'yyyy-mm-dd hh24miss') <> (select distinct to_char(UPLOAD_DATE,'yyyy-mm-dd hh24miss') LISTED from mps_header where rownum < 2)
order by upload_date desc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"LASTED"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>