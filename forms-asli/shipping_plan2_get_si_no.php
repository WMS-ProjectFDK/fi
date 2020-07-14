<?php
	include("../connect/conn.php");
	header("Content-type: application/json");
	$arrData = array();
	$arrNo = 0;
	$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';
	
	$sql = "select h.SI_NO,h.CONSIGNEE_NAME,h.NOTIFY_NAME,h.NOTIFY_NAME_2,h.FORWARDER_NAME,h.EMKL_NAME,h.PLACE_DELI_CODE
		, to_char(s.DATA_DATE, 'DDMMYYYY') as DATA_DATE, to_char(s.ETD, 'DDMMYYYY') as ETD, to_char(s.ETA, 'DDMMYYYY') as ETA, s.SI_NO as ANS
		from SI_HEADER h
		inner join (select distinct a.SI_NO, a.PO_NO from SI_PO a
		            inner join so_header b on a.po_no= b.customer_po_no
		            where b.customer_code='$id') p on h.SI_NO = p.SI_NO
		left join (select SI_NO, DATA_DATE, ETD, ETA, row_number() over( partition by SI_NO order by OPERATION_DATE desc ) as RN
		           from ANSWER where SI_NO is not null) s on h.SI_NO = s.SI_NO
		where s.RN = 1
		order by h.si_no desc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	
	while ($row=oci_fetch_object($result)){
		array_push($arrData, $row);
		$arrNo++;
	}
	echo json_encode($arrData);
?>