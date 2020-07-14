<?php
	include("../connect/conn.php");
	header("Content-type: application/json");
	$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';
	
	$sql = "select distinct a.SI_NO, s.LOAD_PORT_CODE, s.LOAD_PORT,  substr(s.LOAD_PORT, 1, 30) as LOAD_PORT_F, 
		s.DISCH_PORT_CODE, s.DISCH_PORT, substr(s.DISCH_PORT, 1, 30) as DISCH_PORT_F, s.FINAL_DEST_CODE, s.FINAL_DEST, substr(s.FINAL_DEST, 1, 30) as FINAL_DEST_F, 
		s.CONSIGNEE_NAME, s.CONSIGNEE_ADDR1, s.CONSIGNEE_ADDR2, s.CONSIGNEE_ADDR3, s.CONSIGNEE_ATTN, s.CONSIGNEE_TEL, 
		s.GOODS_NAME, a.VESSEL, to_char(a.ETD, 'yyyy-mm-dd') as ETD_F, to_char(a.ETA, 'yyyy-mm-dd') as ETA_F, f1.FORWARDER_CODE as FORWARDER_CODE, 
		f2.FORWARDER_CODE as EMKL_CODE, c.METHOD_TYPE as SHIPPING_TYPE_CODE, LIST_COLLECT(a.SI_NO, ', ') as PO_NO,
    	s.CONSIGNEE_NAME || chr(10) || s.CONSIGNEE_ADDR1 || chr(10) || s.CONSIGNEE_ADDR2 || chr(10) || s.CONSIGNEE_ADDR3 || chr(10) || s.CONSIGNEE_ATTN || chr(10) || s.CONSIGNEE_TEL CONSIGNEE_FULL 
		from (select   SI_NO, min(ANSWER_NO) as ANSWER_NO_MIN, sum(QTY) as QTY_SUM 
		      from ANSWER 
		      where SI_NO is not null and CUSTOMER_CODE  = '$id' 
		      group by SI_NO 
		     ) a_sub,
		     ANSWER a, 
		     SI_HEADER s, 
		     ( select FORWARDER_CODE, FORWARDER 
		       from   FORWARDER 
		       where  DELETE_TYPE is null 
		     ) f1, 
		     ( select FORWARDER_CODE, FORWARDER 
		       from   FORWARDER 
		       where  DELETE_TYPE is null 
		     ) f2, 
		     CARGO_METHOD c 
		 where a.SI_NO is not null 
		   and a_sub.ANSWER_NO_MIN = a.ANSWER_NO 
		   and a_sub.SI_NO         = s.SI_NO 
		   and s.FORWARDER_NAME    = f1.FORWARDER (+) 
		   and s.EMKL_NAME         = f2.FORWARDER (+) 
		   and s.SHIPPING_TYPE     = c.DESCRIPTION (+) 
		 order by a.SI_NO desc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_object($result)){
		array_push($arrData, $row);
		$arrNo++;
	}
	echo json_encode($arrData);
?>