<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct a.slip_no from mte_header a 
		inner join mte_details b on a.slip_no=b.slip_no
		where substr(a.slip_no,0,2)='MT' and a.slip_date >= to_date('2016-01-01','yyyy-mm-dd') and b.item_no not between 1200000 and 1299999
		order by a.slip_no asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"slip_no"=>rtrim($row[0]),
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>