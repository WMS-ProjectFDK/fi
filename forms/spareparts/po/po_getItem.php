<?php
	session_start();
	$result = array();
	$bypil = '';

	$supp = isset($_REQUEST['supp']) ? strval($_REQUEST['supp']) : '';
	$curr = isset($_REQUEST['curr']) ? strval($_REQUEST['curr']) : '';
	$by = isset($_REQUEST['by']) ? strval($_REQUEST['by']) : ''; 
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
    $bypil = "";
	if($by == "ITEM_NO"){
		$bypil = " u.item_no like '%".strtoupper($item)."%' ";
	}elseif($by == "DESCRIPTION"){
		$bypil = " i.description like '%".strtoupper($item)."%' ";
	}

	include("../../../connect/conn.php");
	$rowno=0;
	$rs = "select top 100  u.item_no, 
		i.delete_type,  
		i.description, 
		i.item, com.country_code as origin_code,
		i.uom_q, 
		un.unit,
		cou.country, 
		cu.curr_code, 
		cu.curr_mark, 
		cu.curr_short, 
		u.ESTIMATE_PRICE,
		cu.curr_mark +' '+ cast(u.estimate_price as varchar(100)) as buying_price
		from SP_ITEMMAKER u, sp_item i, sp_unit un, sp_company com, country cou, currency cu
		where u.item_no = i.item_no and i.delete_type is null and u.supplier_code = com.company_code and com.country_code = cou.country_code 
		and i.uom_q = un.unit_code  and u.curr_code = cu.curr_code
		and u.curr_code='$curr' and u.supplier_code='$supp' and 
		$bypil
		order by i.item, u.item_no, com.country_code, ESTIMATE_PRICE";
	$data = sqlsrv_query($connect, strtoupper($rs));
	// echo $rs;

	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$item_no = $items[$rowno]->ITEM_NO;
		$desc = $items[$rowno]->DESCRIPTION;
		$item = $items[$rowno]->ITEM;
		$item_full = $item_no." - ".$desc;
		$items[$rowno]->ITEM_FULL = $item_full;
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>