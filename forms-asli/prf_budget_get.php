<?php
	session_start();
	$result = array();

	include("../connect/conn2.php");

	$rowno=0;
	$rs = "select distinct doc_no,idr_rate,jpy_rate,sgd_rate from ztb_prf_parameter order by doc_no desc";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	$arrBLN = array("01" => "JANUARY",
					"02" => "FEBRUARY",
					"03" => "MARCH",
					"04" => "APRIL",
					"05" => "MAY",
					"06" => "JUNY",
					"07" => "JULY",
					"08" => "AUGUST",
					"09" => "SEPTEMER",
					"10" => "OCTOBER",
					"11" => "NOVEMBER",
					"12" => "DECEMBER");

	while($row = oci_fetch_object($data)) {
		$date = $row->DOC_NO;
		$t = substr($date, 0,4);
		$b = substr($date, 4,2);
		array_push($items, $row);
		$items[$rowno]->DOC = $arrBLN[$b]." ".strtoupper($t);
		$idr = $items[$rowno]->IDR_RATE;
		$items[$rowno]->IDR_RATE = number_format($idr,2);
		$jpy = $items[$rowno]->JPY_RATE;
		$items[$rowno]->JPY_RATE = number_format($jpy,2);
		$sgd = $items[$rowno]->SGD_RATE;
		$items[$rowno]->SGD_RATE = number_format($sgd,2);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>