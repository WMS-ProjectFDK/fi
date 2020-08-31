<?php
    session_start();
    $items = array();
    $rowno=0;
    
	include("../../connect/conn.php");

	$sql = "select PI_NO, PLT_SPEC_NO, 
        CAST(PALLET_UNIT_NUMBER as int) as PALLET_UNIT_NUMBER, 
        CAST(PALLET_CTN_NUMBER as int) as PALLET_CTN_NUMBER, 
        CAST(PALLET_STEP_CTN_NUMBER as int) as PALLET_STEP_CTN_NUMBER,
        CAST(PALLET_HEIGHT as int) as PALLET_HEIGHT, 
        CAST(PALLET_WIDTH as int) as PALLET_WIDTH, 
        CAST(PALLET_DEPTH as int) as PALLET_DEPTH, 
        CAST(PALLET_SIZE_TYPE as int) as PALLET_SIZE_TYPE, 
        CAST(PALLET_WEIGHT as int) as PALLET_WEIGHT
        from PACKING_INFORMATION";
	$data = sqlsrv_query($connect, strtoupper($sql));

	while($row = sqlsrv_fetch_object($data)){
        array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>