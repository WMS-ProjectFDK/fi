<?php
	session_start();
	$vendor = isset($_REQUEST['vendor']) ? strval($_REQUEST['vendor']) : '';
	$m = intval(date('m'));
	$y = date('Y');
	//echo $m.$y;
	
	include("../connect/conn.php");

	$sql = "select distinct a.item_no, b.item, b.description, b.origin_code, b.uom_q, 
    	case a.sts_bundle when 'Y' then 'BUNDLE' else c.unit end as unit,
		case a.sts_bundle when 'Y' then round(a.qty/ a.bundle_qty) else a.qty end as SAFETY, 
    	case a.sts_bundle when 'Y' then round(nvl(e.this_inventory,0)/a.bundle_qty) else nvl(e.this_inventory,0) end as INVENTORY, 
    	case a.sts_bundle when 'Y' then ((round(a.qty/ a.bundle_qty))-(round(nvl(e.e.this_inventory,0)/a.bundle_qty))) 
    	else (a.qty-nvl(e.this_inventory,0)) end as BALANCE 
		from ztb_safety_stock a
		inner join item b on a.item_no=b.item_no
		inner join unit c on b.uom_q = c.unit_code
		left outer join whinventory e on a.item_no= e.item_no
		inner join itemmaker f on a.item_no=f.item_no
		where f.supplier_code = '".$vendor."' and a.qty-nvl(e.e.this_inventory,0) > 0 and a.period = ".$m." and a.year = '".$y."' and 
		a.upload = (select max(upload) from ztb_safety_stock where item_no= a.item_no and period= a.period and year=a.year)
		order by b.description asc";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$mtr = $items[$rowno]->ITEM_NO;
		$s = $items[$rowno]->SAFETY;
		$i = $items[$rowno]->INVENTORY;
		$b = $items[$rowno]->BALANCE;
		$items[$rowno]->VENDOR = $vendor;
		
		/*query untuk PO secara auto FIFO*/
		/*$qry = "select a.po_no, a.po_date, b.line_no, b.item_no,c.item, c.description, b.qty, b.gr_qty, b.qty-b.gr_qty as balance  from po_header a
		inner join po_details b on a.po_no=b.po_no
		inner join item c on b.item_no=c.item_no
		where a.supplier_code='".$vendor."' and b.item_no=".$mtr." and b.qty-b.gr_qty > 0
		order by po_date asc";
		$dataPO = oci_parse($connect, $qry);
		oci_execute($dataPO);*/
		
		$items[$rowno]->SAFETY = number_format($s,2);
		$items[$rowno]->INVENTORY = number_format($i,2);
		$items[$rowno]->BALANCE = number_format($b,2);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>