<?php
	session_start();
	include("../../../connect/conn.php");
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$txt_item_name = isset($_REQUEST['txt_item_name']) ? strval($_REQUEST['txt_item_name']) : '';
	$cmb_subject_code = isset($_REQUEST['cmb_subject_code']) ? strval($_REQUEST['cmb_subject_code']) : '';
	$ck_subject_code = isset($_REQUEST['ck_subject_code']) ? strval($_REQUEST['ck_subject_code']) : '';
	$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

	if ($ck_item_no != "true"){
		$item_no = "a.item_no = '$cmb_item_no' and ";
	}else{
		$item_no = "";
	}

	if ($ck_subject_code != "true"){
		$subject_code = "a.stock_subject_code = '$cmb_subject_code' and ";
	}else{
		$subject_code = "";
	}

	if($item_no =='' AND $subject_code ==''){
		$top = 'top 200';
	}else{
		$top ='';
	}
	

	if ($src != '') {
		$where = "where (a.item like '%$src%' OR a.item_no like '%$src%' OR a.description like '%$src%') 
					AND (a.item_no is not null OR a.item_no != '') and delete_type is null and a.section_code = 100 ";
	}else{
		$where = "where $item_no $subject_code (a.item_no is not null OR a.item_no != '') and delete_type is null and a.section_code = 100";
	}

    $sql = "select $top 
        CAST(a.UPTO_DATE as varchar(11)) as UPTO_DATE, CAST(a.REG_DATE as varchar(11)) as REG_DATE, a.ITEM_NO,a.ITEM, a.DESCRIPTION, a.DESCRIPTION_ORG, a.SPECIFICATION, a.MACHINE_CODE, a.ITEM_TYPE1, a.UNIT_STOCK, a.UOM_Q, a.CURR_CODE, a.REPORTGROUP_CODE, a.CLASS_CODE, a.STOCK_SUBJECT_CODE, a.SAFETY_STOCK, a.SECTION_CODE, a.PURCHASE_LEADTIME,
        b.STOCK_SUBJECT, c.class_1 as class from sp_item a
		inner join SP_STOCK_SUBJECT b on a.STOCK_SUBJECT_CODE = b.STOCK_SUBJECT_CODE
		INNER JOIN SP_CLASS c on a.class_code = c.class_code
		$where
		order by a.item asc";
	$data = sqlsrv_query($connect, strtoupper($sql));
    // echo $sql;
	$items = array();
	$rowno=0;

	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>