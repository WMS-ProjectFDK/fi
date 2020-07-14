<?php
	session_start();
	$result = array();

	$find = isset($_REQUEST['vendorno']) ? strtoupper(strval($_REQUEST['vendorno'])) : '';

	if($find!=''){
		$f = "company_code like '%$find%' or company like '%$find%'";
	}else{
		$f="";
	}

	$where = "where $f";

	include("../connect/conn2.php");

	$rowno=0;
	$rs = "select company_code,company,0 as price from company $where order by company asc";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>