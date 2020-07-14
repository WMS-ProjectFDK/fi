<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$user = isset($_REQUEST['user']) ? strval($_REQUEST['user']) : '';
	// $sql = "select distinct crs_remark, inv_no from answer a
	// 	inner join si_header s on a.si_no = s.si_no
	// 	left join (select distinct ai.inv_no, si.si_no from indication ai inner join answer si on ai.answer_no = si.answer_no)ind on a.si_no=ind.si_no
	// 	where to_char(data_date,'YYYYMM') >= '201807' and crs_remark is not null
	// 	and s.entry_person_code='$user' and inv_no is null";
	$sql = "select distinct crs_remark, inv_no from answer a
		inner join si_header s on a.si_no = s.si_no
		left join (select distinct ai.inv_no, si.si_no from indication ai inner join answer si on ai.answer_no = si.answer_no)ind on a.si_no=ind.si_no
		where to_char(data_date,'YYYYMM') >= '201807' and crs_remark is not null";
		//and inv_no is null";
	$result = oci_parse($connect, $sql);
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