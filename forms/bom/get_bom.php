<?php
session_start();
ini_set('max_execution_time', -1);
include("../../connect/conn.php");

$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
$sts =  isset($_REQUEST['sts']) ? strval($_REQUEST['sts']) : '';

$items = array();
$rowno=0;

if($sts != 'all'){
	$sql  = "select DISTINCT s.UPPER_ITEM_NO, i.ITEM, i.[DESCRIPTION], s.LEVEL_NO, cast(s.OPERATION_DATE as varchar(10)) as input_date 
		from STRUCTURE s 
		inner join item i on s.UPPER_ITEM_NO = i.ITEM_NO
		where s.upper_item_no = '$item_no'
		order by s.UPPER_ITEM_NO" ;
}else{
	$sql = "select DISTINCT s.UPPER_ITEM_NO, i.ITEM, i.[DESCRIPTION], s.LEVEL_NO
		from STRUCTURE s 
		inner join item i on s.UPPER_ITEM_NO = i.ITEM_NO
		order by s.UPPER_ITEM_NO asc, s.LEVEL_NO asc" ;
}

$data_cek = sqlsrv_query($connect, strtoupper($sql));
while($row = sqlsrv_fetch_object($data_cek)){
	array_push($items, $row);
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>
