<?php
	session_start();
	$result = array();
	include("../../connect/conn.php");
	$dn = isset($_REQUEST['dn']) ? strval($_REQUEST['dn']) : '';
	$rowno=0;

    $rs = "select dnd.line_no, dnd.inv_no, CAST(doh.BL_DATE as varchar(10)) as BL_DATE ,dnd.SHIP_NAME, 
        CAST(dnd.ETA as varchar(10)) as ETA, CAST(dnd.ETD as varchar(10)) as ETD, dnd.AMT_O
        from DN_DETAILS dnd
        inner join DO_HEADER doh on dnd.INV_NO=doh.DO_NO
        where dnd.DN_NO='$dn'
        order by dnd.LINE_NO asc";    
	$data = sqlsrv_query($connect, strtoupper($rs)	);
	$items = array();
	
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$a = $items[$rowno]->AMT_O;
		$items[$rowno]->AMT_O = number_format($a,2);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>