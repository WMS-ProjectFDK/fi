<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	//$po_no = isset($_REQUEST['po_no']) ? strval($_REQUEST['po_no']) : '';


	$yearmin = intval(date('Y'))-1;
	$year = date('Y');
	$yearplus = intval(date('Y')+1);
	//echo $yearmin.$year.$yaerplus;
	$sql = " select    h.SI_NO, 
          h.CONSIGNEE_NAME, 
          h.NOTIFY_NAME, 
          h.NOTIFY_NAME_2, 
          h.FORWARDER_NAME, 
          h.EMKL_NAME, 
          h.PLACE_DELI_CODE 
        , to_char(s.DATA_DATE, 'DDMMYYYY') as DATA_DATE 
        , to_char(s.ETD, 'DDMMYYYY') as ETD 
        , to_char(s.ETA, 'DDMMYYYY') as ETA 
        , s.SI_NO as ANS 
   from   SI_HEADER h, 
          ( select distinct SI_NO, PO_NO 
            from   SI_PO 
            where  PO_NO = '17FI076-LR03C1-10' 
          ) p 
      ,   ( select SI_NO, DATA_DATE, ETD, ETA 
                 , row_number() over( partition by SI_NO order by OPERATION_DATE desc ) as RN
              from ANSWER 
             where SI_NO is not null 
          ) s
   where  h.SI_NO = p.SI_NO 
   and    h.SI_NO = s.SI_NO(+)
   and    s.RN(+) = 1   ";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrNo = 0;
	$arrData = array();
	while ($row=oci_fetch_array($result)){
		$arrData[$arrNo] = array(
			"SI_NO"=>rtrim($row[0]),
			"CONSIGNEE_NAME"=>rtrim($row[1]),
			"EMKL_NAME"=>rtrim($row[2])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>