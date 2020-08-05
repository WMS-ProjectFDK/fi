<?php
	session_start();
	$result = array();
	$items = array();
	$rowno=0;

	include("../../connect/conn.php");

	$rs = "select 
        cl.class,
        g.transaction_type,
        g.customer_code,
        g.customer,
        g.customer_person_code,
        g.customer_person,
        g.customer_po_no,
        g.customer_line_no,
        CAST(g.customer_po_date as varchar(10)) customer_po_date
        from grpodr_in g, 
        (select distinct coalesce(replace(class, ' ', ''),'AM-LR-') class, customer_po_no from grpodr_in) cl  
        where 1=1 
        --and g.customer_code='996130'
        and g.customer_po_no=cl.customer_po_no 
        order by class,
                g.customer_code,
                g.customer_po_no,
                g.customer_po_date";
		
	$data = sqlsrv_query($connect, strtoupper($rs));
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>