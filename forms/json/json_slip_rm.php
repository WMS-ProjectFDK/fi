<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct 'MT-'+slip_no as slip_no from ztb_wh_out_det 
        where WO_DATE >= DATEADD(DAY,-90, CAST(GETDATE() as date)) AND 
        WO_DATE <= cast(getdate() as date) and slip_no is not null
        order by slip_no asc";
	$result = sqlsrv_query($connect, $sql);
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"slip_no"=>rtrim($row[0]),
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>