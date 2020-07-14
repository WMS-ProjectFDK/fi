<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct a.wo_no from ztb_m_plan a 
		inner join ztb_wh_kanban_trans b on a.id=b.id
		where a.upload=1 and substr(b.slip_no,0,6)='KANBAN'";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"wo_no"=>rtrim($row[0]),
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>