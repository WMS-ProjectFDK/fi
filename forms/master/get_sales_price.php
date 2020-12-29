<?php
	session_start();
	include("../../connect/conn.php");
	$cmb_item = isset($_REQUEST['cmb_item']) ? strval($_REQUEST['cmb_item']) : '';
	$ck_item = isset($_REQUEST['ck_item']) ? strval($_REQUEST['ck_item']) : '';
    $cmb_supplier = isset($_REQUEST['cmb_supplier']) ? strval($_REQUEST['cmb_supplier']) : '';
	$ck_supplier = isset($_REQUEST['ck_supplier']) ? strval($_REQUEST['ck_supplier']) : '';


	if ($ck_item != "true"){
		$item = "i.item_no='$cmb_item' and ";
	}else{
		$item = "";
    }
    if ($ck_supplier != "true"){
		$supplier = "i.customer_code='$cmb_supplier' and ";
	}else{
		$supplier = "";
	}

	
	$where ="where $item $supplier 1=1";


	$sql = "select i.CUSTOMER_CODE,cc.COMPANY, i.ITEM_NO, it.[DESCRIPTION],i.U_PRICE ,c.CURR_MARK
	from SP_REF i
	inner join item it 
	on i.ITEM_NO  = it.ITEM_NO
	inner join COMPANY cc 
	on i.CUSTOMER_CODE = cc.COMPANY_CODE
	inner join CURRENCY c 
	on i.CURR_CODE = c.CURR_CODE
	$where
	";

	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>