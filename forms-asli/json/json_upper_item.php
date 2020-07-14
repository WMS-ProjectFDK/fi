<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$sql = "select distinct a.upper_item_no, b. description from structure a
		inner join item b on a.upper_item_no=b.item_no
		where b.stock_subject_code=5 and b.description is not null
		order by b.description asc ";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	while($dt_result = oci_fetch_object($data)){
		$arrData[] = array("item_no"=>$dt_result->UPPER_ITEM_NO,
						   "id_name_item"=>$dt_result->UPPER_ITEM_NO.' - '.$dt_result->DESCRIPTION
						  );
	}
	echo json_encode($arrData);
?>