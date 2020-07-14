<?php
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$idx = isset($_REQUEST['index']) ? strval($_REQUEST['index']) : '';
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select 
		(select distinct to_char(UPLOAD_DATE,'yyyy-mm-dd hh24:mi:ss') LISTED from mps_header where rownum < 2)LISTED,
		(select listed from (
			select distinct to_char(UPLOAD_DATE,'yyyy-mm-dd hh24:mi:ss') listed,upload_date 
			from mps_header_rireki 
			where upload_date > (select sysdate - 100 from dual) 
			and to_char(UPLOAD_DATE,'yyyy-mm-dd hh24:mi:ss') <> (select distinct to_char(UPLOAD_DATE,'yyyy-mm-dd hh24:mi:ss') LISTED from mps_header where rownum < 2)
			order by upload_date desc
		)aa where rownum  <2
		)LASTED
		from dual";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"LISTED"=>rtrim($row[0]), 
			"LASTED"=>rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>