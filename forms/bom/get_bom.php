<?php
session_start();
ini_set('max_execution_time', -1);
include("../../connect/conn.php");

$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';

$cmb_item_low = isset($_REQUEST['cmb_item_low']) ? strval($_REQUEST['cmb_item_low']) : '';
$ck_item_low = isset($_REQUEST['ck_item_low']) ? strval($_REQUEST['ck_item_low']) : '';

if ($ck_item_no != "true"){
	$item = "s.upper_item_no = '$item_no' and ";
}else{
	$item = "";
}

if ($ck_item_low != "true"){
	$item_low = "s.lower_item_no = '$cmb_item_low' and ";
}else{
	$item_low = "";
}

$sql  = "select DISTINCT s.UPPER_ITEM_NO,
	i.ITEM,
	i.[DESCRIPTION],
	s.LEVEL_NO,
	cast(s.OPERATION_DATE as varchar(10)) as input_date 
	from STRUCTURE s 
	inner join item i 
	on s.UPPER_ITEM_NO = i.ITEM_NO
	where $item $item_low s.upper_item_no is not null
	order by s.UPPER_ITEM_NO" ;
// echo $sql;
$data_cek = sqlsrv_query($connect, strtoupper($sql));

$items = array();
$rowno=0;

while($row = sqlsrv_fetch_object($data_cek)){
	array_push($items, $row);
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);

?>
