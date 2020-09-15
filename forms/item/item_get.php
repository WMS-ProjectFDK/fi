<?php
	session_start();
	include("../../connect/conn.php");
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$txt_item_name = isset($_REQUEST['txt_item_name']) ? strval($_REQUEST['txt_item_name']) : '';
	$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

	if ($ck_item_no != "true"){
		$item_no = "a.item_no = '$cmb_item_no' and ";
	}else{
		$item_no = " ";
	}

	if ($src != '') {
		$where = "where a.item_no like '%$src%' ";
	}else{
		$where = "where $item_no a.item_no is not null and a.item_no != 0 and delete_type is null ";
	}

	$sql = "select top 200 a.*, b.STOCK_SUBJECT, c.class_1+'-'+c.class_2+'-'+c.class_3 as class from item a
		inner join STOCK_SUBJECT b on a.STOCK_SUBJECT_CODE = b.STOCK_SUBJECT_CODE
		INNER JOIN class c on a.class_code = c.class_code
		$where
		order by a.item asc";
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;

	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>