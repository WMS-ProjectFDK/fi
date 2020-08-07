<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$user = isset($_REQUEST['user']) ? strval($_REQUEST['user']) : '';
	$sql = "select distinct crs_remark, inv_no from answer a
		inner join si_header s on a.si_no = s.si_no
		left join (select distinct ai.inv_no, si.si_no from indication ai inner join answer si on ai.answer_no = si.answer_no)ind on a.si_no=ind.si_no
		where to_char(data_date,'YYYYMM') >= '201807' and crs_remark is not null";
	$result = sqlsrv_query($connect, strtoupper($sql));
	$arrNo = 0;
	$arrData = array();
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"ppbe_no"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>