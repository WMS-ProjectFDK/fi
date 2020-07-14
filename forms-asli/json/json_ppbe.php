<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$user = isset($_REQUEST['user']) ? strval($_REQUEST['user']) : '';
	$sql = "select distinct ppbe_no from ztb_shipping_detail 
		where ppbe_no in (select DISTINCT a.crs_remark from answer a
                      inner join indication b on a.answer_no = b.answer_no
                      where b.inv_no is null and b.inv_line_no is null and b.commit_date is null)
		order by ppbe_no asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrNo = 0;
	$arrData = array();
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"ppbe_no"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>