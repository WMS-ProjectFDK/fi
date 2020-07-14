<?php
	set_time_limit(0);
	include("../../connect/conn.php");
	$items = array();
	$rowno=0;

	$sql = "select wo from ztb_log_kuraire";
	$data = oci_parse($connect, $sql);
	oci_execute($data);
	$i = 0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$a_date = $items[$rowno]->WO;

		$stmt = oci_parse($connect, $a_date);
		$res = oci_execute($stmt);
		echo $rowno;
		echo $a_date;
		echo "<br>";
		$rowno++;
		// if($rowno == 100){
		// 	break;
		// };
	
	}
	// $result["rows"] = $items;
	// echo json_encode($result);
?>