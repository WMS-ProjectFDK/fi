<?php
	session_start();
	$result = array();

	$supp = isset($_REQUEST['supp']) ? strval($_REQUEST['supp']) : '';
	$curr = isset($_REQUEST['curr']) ? strval($_REQUEST['curr']) : '';
	$by = isset($_REQUEST['by']) ? strval($_REQUEST['by']) : ''; 
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';

	if($by == "ITEM_NO"){
		$bypil = " u.item_no='".strtoupper($item)."' ";
	}elseif($by == "DESCRIPTION"){
		$bypil = " i.description like '%".strtoupper($item)."%' ";
	}

	include("../connect/conn.php");
	$rowno=0;

	$rs = "select * from (select distinct u.item_no, 
						  i.delete_type,  
						   i.description, 
						   i.drawing_no, 
						   i.drawing_rev, 
						   i.item, i.origin_code, 
						   i.uom_q, 
						   un.unit,
						   cou.country, 
						   cu.curr_code, 
						   cu.curr_mark, 
						   cu.curr_short, 
						   ltrim(to_char(u.estimate_price,'99999990.000000')) ESTIMATE_PRICE,
						  cu.curr_mark ||' '|| ltrim(to_char(u.estimate_price,'99999990.000000')) buying_price
		from itemmaker u, item i, unit un, country cou, currency cu
		where u.item_no = i.item_no and i.delete_type is null and i.origin_code = cou.country_code (+)
		and i.uom_q = un.unit_code (+) and u.curr_code = cu.curr_code (+) 
		and u.curr_code='$curr' and u.supplier_code='$supp' and i.section_code='100' and 
		$bypil 
		and (u.ITEM_NO is null or u.ALTER_PROCEDURE = (select min(ALTER_PROCEDURE) from ITEMMAKER where ITEM_NO = u.ITEM_NO AND itemmaker.supplier_code=u.supplier_code))
		and i.item_no not in (select distinct item_no from ztb_material_konversi where item_no is not null)
		order by i.item, u.item_no, i.origin_code, ESTIMATE_PRICE)
		where rownum <=100";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
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