<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct xr.curr_code, cr.curr_short, xr.rate, ex_date from ex_rate xr left join currency cr on xr.curr_code=cr.curr_code
		where ex_date=(select max(ex_date) from ex_rate where curr_code=xr.curr_code) and xr.curr_code='8'";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$rate = 1/floatval(rtrim($row[2]));
		$arrData[$arrNo] = array(
			"curr"=>rtrim($row[1]), 
			"rate"=>number_format($rate,2)
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>