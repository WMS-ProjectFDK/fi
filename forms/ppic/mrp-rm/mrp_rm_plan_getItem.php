<?php
	session_start();
	$result = array();
	include("../../../connect/conn.php");
	$s_item = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	$date = isset($_REQUEST['date']) ? strval($_REQUEST['date']) : '';
	$n = isset($_REQUEST['no']) ? strval($_REQUEST['no']) : '';
	$sts = isset($_REQUEST['sts']) ? strval($_REQUEST['sts']) : '';

	$nilai = 'N_'.$n;

	$rowno=0;
	$rs = "select aa.item_no,aa.item,aa.description,aa.standard_price,aa.unit,aa.uom_q,aa.require_date, bb.qty, aa.standard_price * bb.qty as amt from 
		(
		select i.item_no, i.item, i.description, isnull(c.ESTIMATE_PRICE,i.standard_price) as standard_price, 
		u.unit, i.uom_q, '$date' as REQUIRE_DATE from item i 
		inner join unit u on i.uom_q= u.unit_code 
		left join ITEMMAKER c on i.item_no=c.item_no 
		where i.delete_type is null and i.item_no=$s_item and i.stock_subject_code in (0,1,2,4,7) and 
		(c.ITEM_NO is null or c.ALTER_PROCEDURE = (select min(ALTER_PROCEDURE) from ITEMMAKER where ITEM_NO = i.ITEM_NO)) 
		) aa
		left outer join
		(
		select item_no,max(a.qty)  as qty from prf_details a where item_no=$s_item 
		AND qty < 
		(
		select case when q < 0 then q * -1 else q end from 
		(
		select sum(case when $nilai < 0 and no_id in (1,4) then $nilai else $nilai end)
		* max(case when no_id = 5 then $nilai else '0' end) as q 
		from ztb_mrp_data where item_no = $s_item AND no_id in(1,4,5) 
		)dd
		)
		group by item_no
		)bb on aa.item_no=bb.item_no";
	// echo $rs;
	$data = sqlsrv_query($connect, strtoupper($rs));
	$items = array();
	while($row = sqlsrv_fetch_array($data)) {
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>