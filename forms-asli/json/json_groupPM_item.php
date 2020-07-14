<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$sql = "select DISTINCT a.item_no, a.description from item a inner join whinventory cc on a.item_no = cc.item_no where cost_subject_code = '136020' ";
		// where b.item in ('CATHODE CAN','EMD','ZINC POWDER','ZINC OXIDE','GRAPHITE')
		//where a.devided=0
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	while($dt_result = oci_fetch_object($data)){
		$arrData[] = array("item_no"=>$dt_result->ITEM_NO,
						   "id_name_item"=>$dt_result->ITEM_NO.' - '.$dt_result->DESCRIPTION
						  );
	}

	echo json_encode($arrData);
?>