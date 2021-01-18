<?php
	session_start();
	$vendor = isset($_REQUEST['vendor']) ? strval($_REQUEST['vendor']) : '';
	$m = intval(date('m'));
	$y = date('Y');
	//echo $m.$y;
	
	include("../../connect/conn.php");

	$sql = "select item_no, item, description, origin_code, uom_q, unit, safety,inventory,
		case DATENAME(dw, getdate()) when 'Thursday' then balance*3 else balance end as balance
		from (
			select distinct a.item_no, b.item, b.description, b.origin_code, b.uom_q, 
					case a.sts_bundle when 'Y' then 'BUNDLE' else c.unit end as unit,
					case a.sts_bundle when 'Y' then ROUND(coalesce(a.qty/a.bundle_qty,0),0) else a.qty end as SAFETY, 
					case a.sts_bundle when 'Y' then round(coalesce(e.this_inventory,0)/a.bundle_qty,0) else coalesce(e.this_inventory,0) end as INVENTORY, 
					case a.sts_bundle when 'Y' then ((round(coalesce(a.qty/ a.bundle_qty,0),0))-(round(coalesce(e.this_inventory,0)/a.bundle_qty,0)))
					else (a.qty-coalesce(e.this_inventory,0)) end as BALANCE
					from ztb_safety_stock a
					left join item b on a.item_no=b.item_no
					left join unit c on b.uom_q = c.unit_code
					left join whinventory e on a.item_no= e.item_no
					left join itemmaker f on a.item_no=f.item_no
					where f.supplier_code = '".$vendor."' and a.qty-coalesce(e.this_inventory,0) > 0 and a.period = ".$m." and a.year = '".$y."' and 
					a.upload = (select max(upload) from ztb_safety_stock where item_no= a.item_no and period= a.period and year=a.year)
		) ax
		order by ax.description asc";
	// select distinct a.item_no, b.item, b.description, b.origin_code, b.uom_q, 
    //     case a.sts_bundle when 'Y' then 'BUNDLE' else c.unit end as unit,
    //     case a.sts_bundle when 'Y' then ROUND(coalesce(a.qty/a.bundle_qty,0),0) else a.qty end as SAFETY, 
    //     case a.sts_bundle when 'Y' then round(coalesce(e.this_inventory,0)/a.bundle_qty,0) else coalesce(e.this_inventory,0) end as INVENTORY, 
    //     case a.sts_bundle when 'Y' then ((round(coalesce(a.qty/ a.bundle_qty,0),0))-(round(coalesce(e.this_inventory,0)/a.bundle_qty,0)))
    //     else (a.qty-coalesce(e.this_inventory,0)) end as BALANCE 
    //     from ztb_safety_stock a
    //     left join item b on a.item_no=b.item_no
    //     left join unit c on b.uom_q = c.unit_code
    //     left join whinventory e on a.item_no= e.item_no
    //     left join itemmaker f on a.item_no=f.item_no
	// 	where f.supplier_code = '".$vendor."' and a.qty-coalesce(e.this_inventory,0) > 0 and a.period = ".$m." and a.year = '".$y."' and 
	// 	a.upload = (select max(upload) from ztb_safety_stock where item_no= a.item_no and period= a.period and year=a.year)
	// 	order by b.description asc";
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$mtr = $items[$rowno]->ITEM_NO;
		$s = $items[$rowno]->SAFETY;
		$i = $items[$rowno]->INVENTORY;
		$b = $items[$rowno]->BALANCE;
		$items[$rowno]->VENDOR = $vendor;
		$items[$rowno]->SAFETY = number_format($s,2);
		$items[$rowno]->INVENTORY = number_format($i,2);
		$items[$rowno]->BALANCE = number_format($b,2);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>