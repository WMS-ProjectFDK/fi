<?php 
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct item as tipe from item 
		where item_no in (select distinct item_no from ztb_material_konversi)
		order by item asc";
	// and item in ('CATHODE CAN','EMD','ZINC POWDER','ZINC OXIDE','GRAPHITE')
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	while($dt_result = oci_fetch_object($data)){
		$arrData[] = array("tipe"=>$dt_result->TIPE);
	}

	echo json_encode($arrData);
?>