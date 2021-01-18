<?php
	session_start();
	
	$slip_no = isset($_REQUEST['slip_no']) ? strval($_REQUEST['slip_no']) : '';
	$result = array();
	
	include("../../../connect/conn.php");
	$rs = "select a.slip_no, a.line_no, a.item_no, b.item, b.description_org as description, a.qty, a.uom_q, c.unit, 
		a.cost_process_code as COST_SUBJECT_CODE, a.reg_date, cast(a.upto_date as varchar(10)) upto_date, a.wo_no, a.date_code, 
		a.remark, d.this_inventory, sum(a.qty) as sumqty
		from sp_mte_details a
		inner join sp_item b on a.item_no = b.item_no 
		inner join sp_unit c on a.uom_q = c.unit_code
		inner join sp_whinventory d on a.item_no=d.item_no
		where a.slip_no = '$slip_no'
		group by a.slip_no, a.line_no, a.item_no, b.item, b.description_org, a.qty, a.uom_q, c.unit, a.cost_process_code, 
		a.reg_date, a.upto_date, a.wo_no, a.date_code, a.remark, d.this_inventory ";
	$data = sqlsrv_query($connect, strtoupper($rs));


	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);

		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q);

		$a = floatval($items[$rowno]->THIS_INVENTORY);
		$b = floatval($items[$rowno]->SUMQTY);
		$stock = $a - $b;
		$items[$rowno]->STOCK = number_format($stock);

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($items);

?>