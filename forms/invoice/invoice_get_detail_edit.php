<?php
	session_start();
	error_reporting(0);
	$result = array();
	
	$do_no = isset($_REQUEST['do_no']) ? strval($_REQUEST['do_no']) : '';

	include("../../connect/conn.php");
	$rowno=0;

	$rs = "select dod.item_no, dod.customer_part_no, it.description, dod.so_no1 as so_no, dod.so_line_no1 as line_no, dod.customer_po_no1 as customer_po_no, dod.answer_no1 as answer_no, 
		dod.qty1 as delivery_qty, dod.uom_q, dod.u_price, dod.carved_stamp as date_code, dod.origin_code,
		replace(cast(mrk.marks as varchar(max)),char(10),'<br>') as remark_shipping
		from do_details dod
		inner join item it on dod.item_no=it.item_no
		left join DO_MARKS mrk on dod.do_no = mrk.do_no and dod.line_no = mrk.mark_no
		where dod.do_no='$do_no' order by dod.line_no asc";
	$data = sqlsrv_query($connect, strtoupper($rs));
	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$qty = $items[$rowno]->QTY1;
		$items[$rowno]->QTY1 = number_format($qty);

		$edit = "'edit'";
		$ans = "'".$items[$rowno]->ANSWER_NO."'";
		$r = "'".$rowno."'";
		$items[$rowno]->SHIPPING_SET = '<a href="javascript:void(0)" onclick="sett_shipping_mark('.$edit.','.$ans.','.$rowno.')">SET</a>';
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>