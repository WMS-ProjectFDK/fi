<?php
	$cust_po = isset($_REQUEST['cust_po']) ? strval($_REQUEST['cust_po']) : '';
	
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select h.SI_NO, h.CONSIGNEE_NAME, h.NOTIFY_NAME, h.NOTIFY_NAME_2, h.FORWARDER_NAME, h.EMKL_NAME, h.PLACE_DELI_CODE, 
		to_char(s.DATA_DATE, 'DDMMYYYY') as DATA_DATE, to_char(s.ETD, 'DDMMYYYY') as ETD, to_char(s.ETA, 'DDMMYYYY') as ETA, s.SI_NO as ANS 
		from   SI_HEADER h, 
		( select distinct SI_NO, PO_NO from SI_PO where  PO_NO = '7100012725') p,   
		( select SI_NO, DATA_DATE, ETD, ETA, row_number() over( partition by SI_NO order by OPERATION_DATE desc) as RN
		  from ANSWER  where SI_NO is not null) s
		where  h.SI_NO = p.SI_NO and    h.SI_NO = s.SI_NO(+) and    s.RN(+) = 1 ";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"SI_NO"=>rtrim($row[0])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>