<?php
	session_start();
	$result = array();

	include("../../connect/conn.php");
	$s_item =  isset($_REQUEST['s_item']) ? strval($_REQUEST['s_item']) : '';

	$rowno=0;
	$rs = "select i.item_no, i.item, i.description, isnull(c.ESTIMATE_PRICE,i.standard_price) as standard_price, u.unit, i.uom_q
		from item i 
	    inner join unit u on i.uom_q= u.unit_code
	    inner join ITEMMAKER c on i.item_no=c.item_no
		where i.delete_type is null and 
	    (upper(i.item) like '%$s_item%' or upper(i.description) like '%$s_item%' or i.item_no like '%$s_item%') and 
	    i.stock_subject_code in (0,1,2,4,7) and
    	(c.ITEM_NO is null or c.ALTER_PROCEDURE = (select min(ALTER_PROCEDURE) from ITEMMAKER where ITEM_NO = i.ITEM_NO))
		order by i.description";

	$data = sqlsrv_query($connect, strtoupper($rs));
	
	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>