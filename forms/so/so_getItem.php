<?php
	session_start();
	$result = array();
	$items = array();
	$rowno=0;
	$cust = isset($_REQUEST['cust']) ? strval($_REQUEST['cust']) : '';

	include("../../connect/conn.php");

	$rs = "select distinct u.item_no, i.item, i.description, u.customer_part_no, i.origin_code, coalesce(stk.stk_qty,0) stk_qty, 
		un.unit uom_q, cou.country origin, i.class_code, i.supplier_code, cu.curr_mark, u.u_price, 'SP_REF' tbl 
		from  sp_ref u, item i, unit un, country cou, 
		(select item_no,sum(coalesce(this_inventory,0)) stk_qty from whinventory group by item_no) stk,currency cu 
		where u.item_no = i.item_no
		and i.delete_type is null 
		and u.origin_code = i.origin_code 
		and u.item_no = stk.item_no
		and i.origin_code = cou.country_code
		and i.uom_q = un.unit_code
		and i.item_no is not null 
		and u.curr_code = cu.curr_code
		and u.customer_code = '$cust'
		order by i.description ";
		
	$data = sqlsrv_query($connect, strtoupper($rs));
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		// $qty = $items[$rowno]->QTY;
		// $items[$rowno]->QTY = number_format($qty);
		// $items[$rowno]->QTY_2 = number_format($qty);

		// $gr_qty = $items[$rowno]->GR_QTY;
		// $items[$rowno]->GR_QTY = number_format($gr_qty);
		// $items[$rowno]->GR_QTY_2 = number_format($gr_qty);

		// $bal_qty = $items[$rowno]->BAL_QTY;
		// $items[$rowno]->BAL_QTY = number_format($bal_qty);
		// $rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>