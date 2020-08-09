<?php
	session_start();
	$ppbe = isset($_REQUEST['ppbe']) ? strval($_REQUEST['ppbe']) : '';
	$items = array();
	$rowno=0;
	include("../../connect/conn.php");
	//117/H/20
	$sql = "
		
		select b.so_no, b.customer_po_no, a.wo_no, d.description, b.item_no,b.item_no ITEM2,
		a.qty, ceiling(a.pallet) pallet,CONTAINER_NO, 
		c.pallet_ctn, ceiling(a.qty / (c.pallet_pcs/c.pallet_ctn)) CARTON,
		cast(a.gross as decimal(10,2)) gross, 
		cast(a.net as decimal(10,2)) net, 
		cast(a.msm as decimal(10,2)) msm, rowid ROW_ID,ppbe_no,
		cast(a.container_value as decimal(10,2)) container_value, 
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