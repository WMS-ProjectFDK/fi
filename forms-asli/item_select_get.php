<?php
	session_start();
	$result = array();

	include("../connect/conn2.php");

	$rowno=0;
	$rs = "select a.*,b.* from po_header a left join po_details b on a.po_no=b.po_no where b.uom_q='70'"
	/*"select x.po_date, y.item_no as ITEM, z.description as DESCRIP, z.uom_q, a.unit, y.u_price, 0 as qty from (select max(po_date) as po_Date,s.item_no from po_header r
		inner join po_details s on r.po_no = s.po_no group by s.item_no) x
		inner join (select s.item_no, s.u_price, r.po_date from po_header r inner join po_details s on r.po_no = s.po_no) y on x.item_no = y.item_no and x.po_date = y.po_date
		inner join item z on y.item_no = z.item_no
		inner join unit a on z.uom_q = a.unit_code
		where z.description like '%OIL'
		order by x.po_date desc"*/;
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		/*$i = $items[$rowno]->ITEM_NO;
		$items[$rowno]->ITEM = $i;*/
		/*$d = $items[$rowno]->DESCRIPTION;
		$items[$rowno]->DESC = $d;*/
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>	

<!-- 
select x.po_date,y.item_no,y.u_price from 
(select max(po_date) as po_Date,s.item_no from po_header r
inner join po_details s on r.po_no = s.po_no group by s.item_no) x
inner join (select s.item_no, s.u_price, r.po_date from po_header r inner join po_details s on r.po_no = s.po_no) y on x.item_no = y.item_no and x.po_date = y.po_date 
order by x.po_date desc
 -->