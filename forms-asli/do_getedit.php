<?php
	session_start();
	
	$slip_no = isset($_REQUEST['slip_no']) ? strval($_REQUEST['slip_no']) : '';
	$result = array();
	
	include("../connect/conn.php");
	
	/*select a.item_no, b.item, b.description, b.uom_q, c.unit_pl, a.this_inventory,sum(d.qty) as no_approve,
		a.this_inventory - sum(d.qty) as stock, b.cost_process_code, 0 as slip_qty, '-' as wo_no, '-' as date_code, '-' as remark from whinventory a
		inner join item b on a.item_no=b.item_no
		inner join unit  c on b.uom_q= c.unit_code
		inner join mte_details d on a.item_no= d.item_no
		inner join mte_header e on d.slip_no=e.slip_no
		where e.approval_date is NULL
		group by a.item_no, b.item, b.description, b.uom_q, c.unit_pl, a.this_inventory, b.cost_process_code*/
	$rs = "select a.slip_no, a.line_no, a.item_no, b.item, b.description, a.qty, a.uom_q, c.unit_pl, a.cost_process_code, 
		a.reg_date, a.upto_date, a.wo_no, a.date_code, a.remark, d.this_inventory, sum(a.qty) as sumqty
		from mte_details a
		inner join item b on a.item_no = b.item_no 
		inner join unit c on a.uom_q = c.unit_code
		inner join whinventory d on a.item_no=d.item_no
		where a.slip_no = '$slip_no'
		group by a.slip_no, a.line_no, a.item_no, b.item, b.description, a.qty, a.uom_q, c.unit_pl, a.cost_process_code, 
		a.reg_date, a.upto_date, a.wo_no, a.date_code, a.remark, d.this_inventory ";
	$data = oci_parse($connect, $rs);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);

		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q);

		$a = floatval($items[$rowno]->THIS_INVENTORY);
		$b = floatval($items[$rowno]->SUMQTY);
		$stock = $a - $b;
		$items[$rowno]->STOCK = number_format($stock);

		/*
		$p = $items[$rowno]->PRICE;
		$items[$rowno]->PRICE = number_format($p);
		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q);
		$t = $p*$q;
		$items[$rowno]->TOTAL = number_format($t);*/
		//$items[$rowno]->STS = '0';
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($items);

?>