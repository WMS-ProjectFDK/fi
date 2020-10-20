<?php
	session_start();
	$result = array();
	
	$dn_no = isset($_REQUEST['dn_no']) ? strval($_REQUEST['dn_no']) : '';

	include("../../connect/conn.php");
	$rowno=0;
	$rs = "select a.DO_NO, cast(a.BL_DATE AS VARCHAR(10)) AS BL_DATE, a.SHIP_NAME, a.AMT_O, a.AMT_L, 
        CAST(a.eta AS VARCHAR(10)) AS ETA, CAST(a.etd AS VARCHAR(10)) AS ETD, b.DN_NO,
        a.CURR_CODE, dod.CUSTOMER_PO_NO1 as po_no_desc
        from do_header a
        inner join (SELECT DO_NO,
                CUSTOMER_PO_NO1 = STUFF(
                    (SELECT ', ' + DO_NO FROM do_details t1
                    WHERE t1.DO_NO = t2.DO_NO
                    FOR XML PATH ('')
                    ), 1, 1, '') 
                from do_details t2
                group by DO_NO) dod on a.do_no=dod.DO_NO
        left join DN_DETAILS b on a.DO_NO=b.INV_NO
        where b.dn_no = '$dn_no'
        order by b.line_no asc";
	$data = sqlsrv_query($connect, strtoupper($rs));
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