<?php
    error_reporting(0);
	session_start();
	// header("Content-type: application/json");
	include("../../connect/conn.php");
	$items = array();
	$result = array();
	$rowno=0;
    
    $date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
    $date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
    $ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
	// $src = trim(isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '');

	if ($ck_date != "true"){
		$c_date = "c.CR_DATE between '$date_awal' and '$date_akhir' and ";
	}else{
		$c_date = "";
	}

	$where =" where $c_date a.SI_NO is not null ";
	
    $sql  = "select top 1000 CAST(a.CREATE_DATE as varchar(10)) as CREATE_DATE, a.ENTRY_PERSON_CODE, a.IP_ADDRESS, a.SI_NO, a.CONTRACT_NO, a.CUST_SI_NO, b.CUST_PO_NO, 
		a.PERSON_NAME, a.GOODS_NAME, a.LOAD_PORT_CODE, a.LOAD_PORT, a.DISCH_PORT_CODE, a.DISCH_PORT, 
		a.FINAL_DEST_CODE, a.FINAL_DEST, a.PLACE_DELI_CODE, a.PLACE_DELI, a.SHIPPING_TYPE, a.PAYMENT_TYPE, a.PAYMENT_REMARK, 
		a.SPECIAL_INST, replace(a.SPECIAL_INFO,CHAR(13)+CHAR(10),'\n') as SPECIAL_INFO,
		CAST(c.CR_DATE as varchar(10)) as CR_DATE,
		a.SHIPPER_NAME, a.SHIPPER_ADDR1, a.SHIPPER_ADDR2, a.SHIPPER_ADDR3, a.SHIPPER_TEL, a.SHIPPER_ATTN, a.SHIPPER_FAX,
		a.FORWARDER_NAME, a.FORWARDER_ADDR1, a.FORWARDER_ADDR2, a.FORWARDER_ADDR3, a.FORWARDER_TEL, a.FORWARDER_FAX, a.FORWARDER_ATTN,
		a.CONSIGNEE_NAME, a.CONSIGNEE_ADDR1, a.CONSIGNEE_ADDR2, a.CONSIGNEE_ADDR3, a.CONSIGNEE_TEL, a.CONSIGNEE_FAX, a.CONSIGNEE_ATTN,
		a.NOTIFY_NAME, a.NOTIFY_ADDR1, a.NOTIFY_ADDR2, a.NOTIFY_ADDR3, a.NOTIFY_TEL, a.NOTIFY_FAX, a.NOTIFY_ATTN,
		a.NOTIFY_NAME_2, a.NOTIFY_ADDR1_2, a.NOTIFY_ADDR2_2, a.NOTIFY_ADDR3_2, a.NOTIFY_TEL_2, a.NOTIFY_FAX_2, a.NOTIFY_ATTN_2,
		a.EMKL_NAME, a.EMKL_ADDR1, a.EMKL_ADDR2, a.EMKL_ADDR3, a.EMKL_TEL, a.EMKL_FAX, a.EMKL_ATTN,
		doc1.SHEET_NO as SHEET_NO_DOC1, doc1.DOC_NAME as DOC_NAME_DOC1, doc1.DOC_DETAIL as DOC_DETAIL_DOC1,
		doc2.SHEET_NO as SHEET_NO_DOC2, doc2.DOC_NAME as DOC_NAME_DOC2, doc2.DOC_DETAIL as DOC_DETAIL_DOC2,
		doc3.SHEET_NO as SHEET_NO_DOC3, doc3.DOC_NAME as DOC_NAME_DOC3, doc3.DOC_DETAIL as DOC_DETAIL_DOC3,
		doc4.SHEET_NO as SHEET_NO_DOC4, doc4.DOC_NAME as DOC_NAME_DOC4, doc4.DOC_DETAIL as DOC_DETAIL_DOC4
		from SI_HEADER a
		left join (SELECT si_no,
				CUST_PO_NO = STUFF(
					(SELECT ', ' + po_no FROM si_po t1
					WHERE t1.si_no = t2.si_no
					FOR XML PATH ('')
					), 1, 1, '') 
				from si_po t2
				group by si_no) b on b.SI_NO = a.SI_NO
		left join (select s.SI_NO, max(s.CR_DATE) as CR_DATE from ANSWER s
				where s.SI_NO is not null group by s.SI_NO
		) c on c.SI_NO = a.SI_NO
		left join (select SI_NO, SHEET_NO, DOC_NAME, DOC_DETAIL from SI_DOC where LINE_NO=1) doc1 on a.SI_NO = doc1.SI_NO
		left join (select SI_NO, SHEET_NO, DOC_NAME, DOC_DETAIL from SI_DOC where LINE_NO=2) doc2 on a.SI_NO = doc2.SI_NO
		left join (select SI_NO, SHEET_NO, DOC_NAME, DOC_DETAIL from SI_DOC where LINE_NO=3) doc3 on a.SI_NO = doc3.SI_NO
		left join (select SI_NO, SHEET_NO, DOC_NAME, DOC_DETAIL from SI_DOC where LINE_NO=4) doc4 on a.SI_NO = doc4.SI_NO	
		$where
		order by a.operation_date desc";
	$data = sqlsrv_query($connect, strtoupper($sql));
	// echo $sql;
	
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);

		$s = $items[$rowno]->SPECIAL_INFO;
		$items[$rowno]->SPECIAL_INFO = str_replace('\N', '\n', $s);

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>