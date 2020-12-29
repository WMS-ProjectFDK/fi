<?php
	include("../../../../connect/conn.php");
    header("Content-type: application/json");
    
    $sql = "select cost_subject_code, cost_subject_name, '['+cost_subject_code+'] '+cost_subject_name AS comb_subject
        from sp_costsubject 
        order by cost_subject_code asc";
	$result = sqlsrv_query($connect, strtoupper($sql));
	
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"COST_SUBJECT_CODE"=>rtrim($row[0]), 
			"COST_SUBJECT_NAME"=>rtrim($row[1]),
			"COMB_SUBJECT_NAME"=>rtrim($row[2])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>