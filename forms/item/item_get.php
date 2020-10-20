<?php
	session_start();
	include("../../connect/conn.php");
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
					AND a.item_no is not null and a.item_no != 0 and delete_type is null and a.section_code = 100 ";
	}else{
		$where = "where $item_no $subject_code a.item_no is not null and a.item_no != 0 and delete_type is null and a.section_code = 100";
	}

	$sql = "select $top a.*, b.STOCK_SUBJECT, c.class_1+'-'+c.class_2+'-'+c.class_3 as class from item a
		inner join STOCK_SUBJECT b on a.STOCK_SUBJECT_CODE = b.STOCK_SUBJECT_CODE
		INNER JOIN class c on a.class_code = c.class_code
		$where
		order by a.item asc";
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;

	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>