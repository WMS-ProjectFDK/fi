<?php
	session_start();
	include("../connect/conn.php");
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	
	$sql = "select distinct a.item_no, a.item, a.description, a.origin_code, a.uom_q, b.unit, c.this_inventory as inventory, pod.balance, pod.balance as blc
		from item a 
		left join (select item_no, sum(bal_qty) as balance
	      from po_details where item_no=2211524 and bal_qty > 0 and eta <= (select sysdate from dual)
	      group by item_no) pod on a.item_no = pod.item_no
		inner join unit b on a.uom_q = b.unit_code
		inner join whinventory c on a.item_no= c.item_no
		where item_no=$item_no ";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$i = $items[$rowno]->INVENTORY;
		$b = $items[$rowno]->BALANCE;

		$items[$rowno]->INVENTORY = number_format($i);
		$items[$rowno]->BALANCE = number_format($b);

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>