<?php
    // error_reporting(0);
	session_start();
    header("Content-type: application/json");
	$items = array();
	$result = array();
	$rowno=0;
    
    $date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
    $date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
    $ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
	$src = trim(isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '');

	if ($ck_date != "true"){
		$c_date = "c.CR_DATE between '$date_awal' and '$date_akhir' and ";
	}else{
		$c_date = "";
	}

	if ($src != '') {
		$where =" where $c_date 
			(b.CUST_PO_NO like '$src%' OR a.CONSIGNEE_NAME like '$src%' OR a.SI_NO like '$src%') AND a.SI_NO is not null ";
	}else{
		$where =" where $c_date a.SI_NO is not null ";
	}
	
	include("../../connect/conn.php");
    $sql  = "select top 150 CAST(a.CREATE_DATE as varchar(10)) as CREATE_DATE, a.ENTRY_PERSON_CODE, a.IP_ADDRESS, a.SI_NO, a.CONTRACT_NO, a.CUST_SI_NO, b.CUST_PO_NO, 
		a.PERSON_NAME, a.GOODS_NAME, a.SHIPPER_NAME, a.LOAD_PORT_CODE, a.LOAD_PORT, a.DISCH_PORT_CODE, a.DISCH_PORT, 
		a.FINAL_DEST_CODE, a.FINAL_DEST, a.PLACE_DELI_CODE, a.PLACE_DELI, a.SHIPPING_TYPE, a.PAYMENT_TYPE, a.PAYMENT_REMARK, 
		a.FORWARDER_NAME, a.SPECIAL_INST, a.SPECIAL_INFO, a.CONSIGNEE_NAME, a.NOTIFY_NAME, a.NOTIFY_NAME_2, a.EMKL_NAME, 
		CAST(c.CR_DATE as varchar(10)) as CR_DATE
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
		$where
		order by a.si_no desc";
	$data = sqlsrv_query($connect, strtoupper($sql));
	// echo $sql;
	
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	echo json_encode($items);
?>