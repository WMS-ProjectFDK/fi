<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$yearmin = intval(date('Y'))-1;
	$year = date('Y');
	$yearplus = intval(date('Y')+1);
	//echo $yearmin.$year.$yaerplus;
	$sql = "select distinct work_order , po_no , cast(Item_no as varchar(10)) item_no  from mps_header 
		union all
		select distinct '' ,po_no,''  from mps_header
		union all
		select distinct '' ,'',cast(item_no as varchar(10)) + ' - ' + item_name Item_no  from mps_header
		order by WORK_ORDER desc";
	$result = sqlsrv_query($connect, $sql);
	
	$arrNo = 0;
	$arrData = array();
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"work_order"=>rtrim($row[0]),
			"po_no"=>rtrim($row[1]),
			"item_no"=>rtrim($row[2])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>