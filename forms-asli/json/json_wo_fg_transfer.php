<?php
	header("Content-type: application/json");
	include("../../connect/conn.php");
	$s = isset($_REQUEST['s']) ? strval($_REQUEST['s']) : '';
	
	if ($s == 'temp'){
		$sql = "select distinct a.item_no, a.item_name, a.work_order, sum(a.qty) as qty_mps, nvl(b.slip_quantity,0) as qty_pi 
			from mps_header a
			left join (SELECT wo_no, sum(slip_quantity) as slip_quantity FROM production_income GROUP BY wo_no) b on a.work_order = b.wo_no
			--where b.slip_quantity <> 0
			group by a.item_no, a.item_name, a.work_order, b.slip_quantity";
	}else{
		$sql = "select distinct a.item_no, a.item_name, a.work_order, sum(a.qty) as qty_mps, b.slip_quantity as qty_pi 
			from mps_header a
			left join (SELECT wo_no, sum(slip_quantity) as slip_quantity FROM production_income GROUP BY wo_no) b on a.work_order = b.wo_no
			group by a.item_no, a.item_name, a.work_order, b.slip_quantity";
	}

	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;

	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"wo_no"=>rtrim($row[2]),
			"qty_mps"=>rtrim($row[3]),
			"qty_pi"=>rtrim($row[4]),
			"item"=>rtrim($row[0]).'-'.rtrim($row[1])
		);
		$arrNo++;
	}

	echo json_encode($arrData);
?>