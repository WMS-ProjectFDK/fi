<?php
	include("../connect/conn.php");
	ini_set('max_execution_time', -1);
	session_start();
	$items = array();
	$rowno=0;

	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	
	$cek = "select item.ITEM_NO,item.DESCRIPTION,pallet_pcs QTY_PCS,pallet_pcs/pallet_ctn QTY_BOX,STEP,GW_PALLET GW,NW_PALLET NW,CARTON_HEIGHT,PANJANG_PALLET,LEBAR_PALLET,TWO_FEET ,FOUR_FEET
		from ztb_item
		inner join ITEM on ztb_item.item_no = item.item_no
		where item.item_no ='$item_no'" ;
	$data_cek = oci_parse($connect, $cek);
	oci_execute($data_cek);

	while($row = oci_fetch_object($data_cek)){
		array_push($items, $row);
		$rowno++;
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
?>