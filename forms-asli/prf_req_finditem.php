<?php
	session_start();
	$result = array();

	$find = isset($_REQUEST['find']) ? strtoupper(strval($_REQUEST['find'])) : '';

	if($find!=''){
		$f = "z.description like '%$find%' or y.item_no like '%$find%'";
	}else{
		$f="";
	}

	$where = "where $f";

	include("../connect/conn2.php");

	$rowno=0;
	$rs = "select x.po_date, y.item_no, z.description, z.uom_q, a.unit, y.u_price from 
		(select max(po_date) as po_date,s.item_no from po_header r
		inner join po_details s on r.po_no = s.po_no group by s.item_no) x
		inner join (select s.item_no, s.u_price, r.po_date from po_header r inner join po_details s on r.po_no = s.po_no) y on x.item_no = y.item_no and x.po_date = y.po_date
		inner join item z on y.item_no = z.item_no inner join unit a on z.uom_q = a.unit_code $where
		order by z.description asc, x.po_date desc";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$i = $items[$rowno]->U_PRICE;
		$items[$rowno]->U_PRICE = number_format($i);
		$id = 0;
		$items[$rowno]->ID = $id;
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>