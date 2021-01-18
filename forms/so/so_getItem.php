<?php
	session_start();
	$result = array();
	$items = array();
	$rowno=0;

	$cust = isset($_REQUEST['cust']) ? strval($_REQUEST['cust']) : '';
	$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

	include("../../connect/conn.php");

	$rs = "select distinct u.item_no, i.item, i.description, u.customer_part_no, i.origin_code, coalesce(stk.stk_qty,0) stk_qty, 
		un.unit uom_q, cou.country origin, i.class_code, i.supplier_code, cu.curr_mark, u.u_price, 'SP_REF' tbl,
		isnull(i.DATE_CODE_TYPE,'MM-YYYY') as DATE_CODE_TYPE, isnull(i.DATE_CODE_MONTH,0) as DATE_CODE_MONTH, 
		isnull(zi.PALLET_CTN,0) as PALLET_CTN, isnull(zi.PALLET_PCS,0) as PALLET_PCS, 
		CAST(isnull(zi.PALLET_PCS,0)/isnull(zi.PALLET_CTN,0) as int) as CARTON_PCS
		from  sp_ref u
		left join item i on u.item_no = i.item_no AND u.origin_code = i.origin_code
		left join unit un on i.uom_q = un.unit_code
		left join country cou on  i.origin_code = cou.country_code
		left join (select item_no,sum(coalesce(this_inventory,0)) stk_qty from whinventory group by item_no) stk on u.item_no = stk.item_no
		left join currency cu on u.curr_code = cu.curr_code
		left join ztb_item zi on i.item_no = zi.ITEM_NO
		where i.delete_type is null 
		and i.item_no is not null 
		and u.customer_code = '$cust'
		and u.item_no like '%$src%'
		order by i.description ";
	$data = sqlsrv_query($connect, strtoupper($rs));
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>