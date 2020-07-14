<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$sql = "select DISTINCT a.item_no, b.description from ztb_material_konversi a
		inner join item b on a.item_no=b.item_no ";
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