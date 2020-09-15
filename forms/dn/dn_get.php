<?php
	session_start();
    header("Content-type: application/json");
    
    $date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
    $cust_no = isset($_REQUEST['cust_no']) ? strval($_REQUEST['cust_no']) : '';
    $cmb_cust = isset($_REQUEST['cmb_cust']) ? strval($_REQUEST['cmb_cust']) : '';
    $ck_cust = isset($_REQUEST['ck_cust']) ? strval($_REQUEST['ck_cust']) : '';
    $srce = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';
	$src = trim($srce);

	if ($ck_date != "true"){
		$dn_date = "dnh.dn_date between '$date_awal' and '$date_akhir' and ";
	}else{
		$dn_date = "";
	}

	if ($ck_cust != "true"){
		$cust = "dnh.customer_code = '".trim($cust_no)."' and ";
	}else{
		$cust = "";
	}	

	if ($src !='') {
		$where ="where dnh.dn_no like '%$src%' and dnh.dn_no is not null";
	}else{
		$where ="where $dn_date $cust dnh.dn_no is not null";
	}
	
	include("../../connect/conn.php");

	$sql  = "select TOP 150 dnh.dn_no, CAST(dnh.dn_date as varchar(10)) as dn_date, dnh.customer_code, com.COMPANY,
        sum(dnd.AMT_O) as AMT_O, count(dnd.line_no) as ITEM
        from dn_header dnh
        inner join dn_details dnd on dnh.DN_NO=dnd.DN_NO
        left join company com on dnh.CUSTOMER_CODE=com.COMPANY_CODE
        $where
        group by dnh.dn_no, dnh.dn_date, dnh.customer_code, com.COMPANY
        ORDER BY dnh.dn_date desc"; 
	$data = sqlsrv_query($connect, strtoupper($sql));
	$items = array();
	$rowno=0;
	$FromMRP=0;
	while($row = sqlsrv_fetch_object($data)){
        array_push($items, $row);
        
        $a = $items[$rowno]->AMT_O;
        $items[$rowno]->AMT_O = number_format($a,2);
        
		// if ($items[$rowno]->STS == '0'){
		// 	if ($items[$rowno]->CUSTOMER_PO_NO=='') {
		// 		$items[$rowno]->status = '<span style="color:red;font-size:11px;"><b>NOT APPROVED</b></span>';
		// 	}else {
		// 		$items[$rowno]->status = '<span style="color:red;font-size:11px;"><b>FROM MRP</b></span>';
		// 	}
		// }else{
		// 	if ($items[$rowno]->CUSTOMER_PO_NO == '') {
		// 		$items[$rowno]->STATUS = '<span style="color:blue;font-size:11px;"><b>APPROVED</b></span>';
		// 	}else {
		// 		$items[$rowno]->STATUS = '<span style="color:blue;font-size:11px;"><b>FROM MRP</b></span>';
		// 	}
		// }

		// if ($items[$rowno]->JUM_PO == '0'){
		// 	$items[$rowno]->JUMLAH_PO = '<span style="color:red;font-size:11px;"><b>Not Order Processing</b></span>';
		// }else{
		// 	$items[$rowno]->JUMLAH_PO = '<span style="color:blue;font-size:11px;"><b>Order Processing</b></span>';
		// }
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>