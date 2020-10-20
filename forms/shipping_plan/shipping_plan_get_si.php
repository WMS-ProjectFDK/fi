<?php
	include("../../connect/conn.php");
	session_start();
	$cust_po_no = isset($_REQUEST['cust_po_no']) ? strval($_REQUEST['cust_po_no']) : '';
	$ck_sample = isset($_REQUEST['ck_sample']) ? strval($_REQUEST['ck_sample']) : '';
	$items = array();
	$rowno=0;
	$where = '';

	if ($ck_sample != "true"){
		$where = " where PO_NO like '%$cust_po_no%' ";
	}else{
		$where = " where si_no='SI FOR SAMPLE' ";
	}

	$sql = "select distinct h.SI_NO, h.CONSIGNEE_NAME, h.NOTIFY_NAME, h.NOTIFY_NAME_2, h.FORWARDER_NAME, h.EMKL_NAME, h.PLACE_DELI_CODE,
		CAST(s.DATA_DATE as varchar) as DATA_DATE, CAST(s.ETD as varchar) as ETD, CAST(s.ETA as varchar) as ETA, h.SI_NO as ANS 
		from SI_HEADER h
		inner join (select distinct SI_NO, PO_NO from SI_PO $where) p on h.SI_NO = p.SI_NO   
					left outer join (select SI_NO, DATA_DATE, ETD, ETA, row_number() over( partition by SI_NO order by OPERATION_DATE desc) as RN
					from ANSWER 
					where SI_NO is not null 
				   ) s on p.SI_no = s.SI_no" ;
	// echo $sql;
	$data = sqlsrv_query($connect, strtoupper($sql));
	
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
?>