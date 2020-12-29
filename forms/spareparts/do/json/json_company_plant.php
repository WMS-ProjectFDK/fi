<?php
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$idx = isset($_REQUEST['index']) ? strval($_REQUEST['index']) : '';
	include("../../../../connect/conn.php");
	header("Content-type: application/json");
    $sql = "select cp.cost_process_code, cp.cost_process_name, cp.cost_process_code+' - '+cp.cost_process_name as comb_CP 
        from sp_costprocess cp
        order by cp.cost_process_name asc";
	$result = sqlsrv_query($connect, strtoupper($sql));
	
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"COMPANY_CODE"=>rtrim($row[0]), 
			"COMPANY"=>rtrim($row[1]),
			"COMB_COMPANY"=>rtrim($row[0])." - ".rtrim($row[1])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>