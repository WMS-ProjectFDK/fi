<?php
	session_start();
	include("../../connect/conn.php");
	$asin = isset($_REQUEST['asin']) ? strval($_REQUEST['asin']) : '';
	$amz_po = isset($_REQUEST['amz_po']) ? strval($_REQUEST['amz_po']) : '';

	$sql = "select distinct WO, ASIN, AMAZON_PO_NO, FROM_CARTON, TO_CARTON, NO, SSCC_NO,
        TOTAL_CARTON, QUANTITY, ADDRESS1, ADDRESS2, ADDRESS3, ADDRESS4
        from ztb_amazon_wo_details 
		where asin = '$asin' AND amazon_po_no = '$amz_po'
		order by from_carton asc";
	$data = sqlsrv_query($connect, $sql);
    // echo $sql;
	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$t = $items[$rowno]->TO_CARTON;
		$f = $items[$rowno]->FROM_CARTON;

		$items[$rowno]->TO_CARTON = number_format($t);
		$items[$rowno]->FROM_CARTON = number_format($f);

		$rowno++;
	}

	$result["rows"] = $items;
	echo json_encode($result);
?>