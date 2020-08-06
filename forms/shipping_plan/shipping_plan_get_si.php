<?php
	include("../../connect/conn.php");
	session_start();
	$cust_po_no = isset($_REQUEST['cust_po_no']) ? strval($_REQUEST['cust_po_no']) : '';

	$sql = "select h.SI_NO, 
		        h.CONSIGNEE_NAME, 
		        h.NOTIFY_NAME, 
		        h.NOTIFY_NAME_2, 
		        h.FORWARDER_NAME, 
		        h.EMKL_NAME, 
		        h.PLACE_DELI_CODE,
		        to_char(s.DATA_DATE, 'DDMMYYYY') as DATA_DATE,
		        to_char(s.ETD, 'DDMMYYYY') as ETD,
		        to_char(s.ETA, 'DDMMYYYY') as ETA,
		        s.SI_NO as ANS 
		 from   SI_HEADER h, 
		        ( select distinct SI_NO, PO_NO 
		          from   SI_PO 
		          where  PO_NO = '$cust_po_no'
		        ) p 
		    ,   ( select SI_NO, DATA_DATE, ETD, ETA 
		               , row_number() over( partition by SI_NO order by OPERATION_DATE desc ) as RN
		            from ANSWER 
		           where SI_NO is not null 
		        ) s
		 where  h.SI_NO = p.SI_NO 
		 and    h.SI_NO = s.SI_NO(+)
		 and    s.RN(+) = 1" ;

	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>