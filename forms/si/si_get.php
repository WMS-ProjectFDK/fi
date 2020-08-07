<?php
    // error_reporting(0);
	session_start();
    header("Content-type: application/json");
    $items = array();
	$rowno=0;
    
    $date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
    $date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
    $ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
    $cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
    $ck_si_no = isset($_REQUEST['ck_si_no']) ? strval($_REQUEST['ck_si_no']) : '';
    $cmb_consignee = isset($_REQUEST['cmb_consignee']) ? strval($_REQUEST['cmb_consignee']) : '';
    $ck_consignee  = isset($_REQUEST['ck_consignee ']) ? strval($_REQUEST['ck_consignee ']) : '';
    $cmb_cust_po = isset($_REQUEST['cmb_cust_po']) ? strval($_REQUEST['cmb_cust_po']) : '';
    $ck_cust_po = isset($_REQUEST['ck_cust_po']) ? strval($_REQUEST['ck_cust_po']) : '';
	$srce = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';
	$src = trim($srce);

	// if ($ck_date != "true"){
	// 	$prf_date = "a.prf_date between '$date_awal' and '$date_akhir' and ";
	// }else{
	// 	$prf_date = "";
	// }

	// if ($ck_item_no != "true"){
	// 	$item_no = " exists(select * from PRF_DETAILS where item_no = $cmb_item_no and prf_no = a.PRF_NO)  and ";
	// }else{
	// 	$item_no = "";
	// }

	// if ($ck_prf != "true"){
	// 	$prf = "a.prf_no = '".trim($cmb_prf_no)."' and ";
	// }else{
	// 	$prf = "";
	// }	

	// if ($src !='') {
	// 	$where ="where a.prf_no like '%$src%' and a.section_code=100";
	// }else{
	// 	$where ="where $prf_date $item_no $prf a.section_code=100 ";
	// }
	
	include("../../connect/conn.php");

	#PRF 
    $sql  = "select a.CREATE_DATE, a.ENTRY_PERSON_CODE, a.IP_ADDRESS, a.SI_NO, a.CONTRACT_NO, a.CUST_SI_NO, b.CUST_PO_NO, a.PERSON_NAME, 
        a.GOODS_NAME, a.SHIPPER_NAME, a.LOAD_PORT_CODE, a.LOAD_PORT, a.DISCH_PORT_CODE, a.DISCH_PORT, a.FINAL_DEST_CODE, a.FINAL_DEST, 
        a.PLACE_DELI_CODE, a.PLACE_DELI, a.SHIPPING_TYPE, a.PAYMENT_TYPE, a.PAYMENT_REMARK, a.FORWARDER_NAME, a.SPECIAL_INST, a.SPECIAL_INFO, 
        a.CONSIGNEE_NAME, a.NOTIFY_NAME, a.NOTIFY_NAME_2, a.EMKL_NAME, CAST(c.CR_DATE as varchar(10)) as CR_DATE
        from SI_HEADER a
        left join (SELECT L.SI_no , 
				   STUFF (
					(SELECT ',' + W.PO_NO AS [text()]
					 FROM si_po AS W
                     WHERE W.SI_no = L.SI_no
                     FOR XML PATH('')
					), 1, 1, ''
				   ) as CUST_PO_NO
                   FROM SI_HEADER L
                  ) b on b.SI_NO = a.SI_NO
        left join (select s.SI_NO, max(s.CR_DATE) as CR_DATE from ANSWER s
                    where s.SI_NO is not null group by s.SI_NO
                  ) c on c.SI_NO = a.SI_NO
        order by a.SI_NO desc";
	$data = sqlsrv_query($connect, strtoupper($sql));
    // echo $sql;
	while($row = sqlsrv_fetch_array($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>