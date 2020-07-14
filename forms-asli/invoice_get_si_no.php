<?php
	include("../connect/conn.php");
	header("Content-type: application/json");
	$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';
	$term = isset($_REQUEST['term']) ? strval($_REQUEST['term']) : '';

	if ($term == 'FOB'){
		$where = "where a.SI_NO is not null AND substr(LIST_COLLECT(a.SI_NO, ', '),0,5) != 'FIENR' ";
	}else{
		$where = "where a.SI_NO is not null ";
	}
	
	$sql = "select * from (
		select distinct a.SI_NO, s.LOAD_PORT_CODE, s.LOAD_PORT,  substr(s.LOAD_PORT, 1, 30) as LOAD_PORT_F, s.person_name,a_sub.crs_remark PPBE,
				s.DISCH_PORT_CODE, s.DISCH_PORT, substr(s.DISCH_PORT, 1, 30) as DISCH_PORT_F, s.FINAL_DEST_CODE, s.FINAL_DEST, substr(s.FINAL_DEST, 1, 30) as FINAL_DEST_F, 
				s.CONSIGNEE_NAME, s.CONSIGNEE_ADDR1, s.CONSIGNEE_ADDR2, s.CONSIGNEE_ADDR3, s.CONSIGNEE_ATTN, s.CONSIGNEE_TEL, 
				s.GOODS_NAME, a.VESSEL, to_char(a.ETD, 'yyyy-mm-dd') as ETD_F, to_char(a.ETA, 'yyyy-mm-dd') as ETA_F, f1.FORWARDER_CODE as FORWARDER_CODE, 
				f2.FORWARDER_CODE as EMKL_CODE, c.METHOD_TYPE as SHIPPING_TYPE_CODE, LIST_COLLECT(a.SI_NO, ', ') as PO_NO,
		    s.CONSIGNEE_NAME || chr(13) || chr(10) || s.CONSIGNEE_ADDR1 || chr(13) || chr(10) || s.CONSIGNEE_ADDR2 || chr(13) || chr(10) || 
		    s.CONSIGNEE_ADDR3 || chr(13) || chr(10) || s.CONSIGNEE_ATTN || chr(13) || chr(10) || s.CONSIGNEE_TEL CONSIGNEE_FULL,
        	s.PAYMENT_TYPE, s.PAYMENT_REMARK, to_char(a.stuffy_date,'YYYY-MM-DD') as ex_fact_date, a_sub.rmk as REMARK
				from (select   a.SI_NO, min(a.ANSWER_NO) as ANSWER_NO_MIN, sum(a.QTY) as QTY_SUM, b.rmk, a.crs_remark 
				      from ANSWER a
              inner join (select aa.si_no, max(ab.remark) as rmk from answer aa inner join indication ab on aa.answer_no = ab.answer_no group by aa.si_no) b on a.si_no = b.si_no
              where a.SI_NO is not null and a.CUSTOMER_CODE  = '$id' group by a.SI_NO, b.rmk, a.crs_remark) a_sub
		    LEFT join ANSWER a on a_sub.ANSWER_NO_MIN = a.ANSWER_NO 
		    LEFT join SI_HEADER s on a_sub.SI_NO = s.SI_NO 
				LEFT join ( select FORWARDER_CODE, FORWARDER from FORWARDER where DELETE_TYPE is null) f1 on s.FORWARDER_NAME = f1.FORWARDER
		    LEFT join ( select FORWARDER_CODE, FORWARDER from FORWARDER where DELETE_TYPE is null) f2 on s.EMKL_NAME = f2.FORWARDER
		    LEFT join CARGO_METHOD c on s.SHIPPING_TYPE = c.DESCRIPTION
		    $where
		    order by a.SI_NO desc)
		where rownum <=250";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_object($result)){
		array_push($arrData, $row);
		$po = $arrData[$arrNo]->PO_NO;
		$arrData[$arrNo]->PO_NO = str_replace(",", "<br>", $po);
		$arrNo++;
	}
	echo json_encode($arrData);
?>