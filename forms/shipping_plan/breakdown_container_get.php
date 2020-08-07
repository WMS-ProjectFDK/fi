<?php
	session_start();
	$ppbe = isset($_REQUEST['ppbe']) ? strval($_REQUEST['ppbe']) : '';
	$items = array();
	$rowno=0;
	include("../../connect/conn.php");
	
	$sql = "select b.so_no, b.customer_po_no, a.wo_no, d.description, b.item_no,b.item_no ITEM2,
		a.qty, ceil(a.pallet) pallet,CONTAINER_NO, 
		c.pallet_ctn, ceil(a.qty / (c.pallet_pcs/c.pallet_ctn)) CARTON,
		ltrim(to_char(a.gross,'99999990.00')) gross, 
		ltrim(to_char(a.net,'99999990.00')) net, 
		ltrim(to_char(a.msm,'99999990.000')) msm, 
		cast(a.ROWID as varchar(50)) ROW_ID,
		ltrim(to_char(a.container_value,'99999990.000')) container_value, 
		a.containers, 'OLD' as sts,a.answer_no, a.tw, a.enr
		from ztb_shipping_detail a
		inner join answer b on a.answer_no = b.answer_no
		inner join ztb_item c on a.item_no= c.item_no
		inner join item d on a.item_no = d.item_no
		where a.ppbe_no='$ppbe'
		order by CONTAINER_NO";
    $data = sqlsrv_query($connect, strtoupper($sql));
    
	$new_container = "";
	$old_container = "xxyy";
	$totalPallet = 0;
	$totalqty = 0;
	$totalcarton = 0;
	$totalGW = 0;
	$totalNW = 0;
	$totalMSM = 0;
	$Totalcontainer_value = 0;
	$flag = 1;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$q = $items[$rowno]->ITEM_NO;
		$r = $items[$rowno]->ROW_ID;
		$new_container = $items[$rowno]->CONTAINER_NO;
		$items[$rowno]->ITEM_NO = '<a href="javascript:void(0)" title="ITEM_NO" onclick="info_item('.$q.')"  style="text-decoration: none; color: black;">'.$q.'</a>';
		$items[$rowno]->SAVE = '<a href="javascript:void(0)" title="ITEM_NO" onclick="con_save()"  style="text-decoration: none; color: blue;">Save</a>';
		$totalPallet = $totalPallet + $items[$rowno]->PALLET;
		$totalqty = $totalqty + $items[$rowno]->QTY;
		$totalcarton = $totalcarton + $carton;
		$totalGW = $totalGW + $items[$rowno]->GROSS;
		$totalNW = $totalNW + $items[$rowno]->NET;
		$totalMSM = $totalMSM + $items[$rowno]->MSM;
		$Totalcontainer_value = $Totalcontainer_value + $items[$rowno]->CONTAINER_VALUE;
		$rowno++;

		$old_container = $new_container;
	}

	$result["rows"] = $items;
	echo json_encode($result);
?>