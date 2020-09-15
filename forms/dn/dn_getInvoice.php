<?php
	session_start();
    $result = array();
    $items = array();

	include("../../connect/conn.php");
    $cust_no_add = isset($_REQUEST['cust_no_add']) ? strval($_REQUEST['cust_no_add']) : '';
    $bl_date_a = isset($_REQUEST['bl_date_a']) ? strval($_REQUEST['bl_date_a']) : '';
    $bl_date_z = isset($_REQUEST['bl_date_z']) ? strval($_REQUEST['bl_date_z']) : '';

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
        where customer_code = $cust_no_add
        and bl_date between '$bl_date_a' and '$bl_date_z'
        and b.dn_no is null
        order by a.bl_date asc";
	$data = sqlsrv_query($connect, strtoupper($rs));
    
	while($row = sqlsrv_fetch_object($data)) {
        array_push($items, $row);

        $a = $items[$rowno]->AMT_O;
        $items[$rowno]->AMT_O = number_format($a,2);

        $b = $items[$rowno]->AMT_L;
        $items[$rowno]->AMT_L = number_format($b,2);
		$rowno++;
	}
    
    $result["rows"] = $items;
	echo json_encode($result);
?>