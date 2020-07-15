<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$yearmin = intval(date('Y'))-1;
	$year = date('Y');
	$yearplus = intval(date('Y')+1);
	//echo $yearmin.$year.$yaerplus;
	$sql = "select distinct po_no, format(po_date,'yyyy') from po_header 
		where format(po_date,'yyyy') = '$yearmin'
		OR format(po_date,'yyyy') = '$year' 
		OR format(po_date,'yyyy') = '$yearplus' 
		order by po_no asc";
	$result = sqlsrv_query($connect, $sql);
	$arrNo = 0;
	$arrData = array();
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"po_no"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>