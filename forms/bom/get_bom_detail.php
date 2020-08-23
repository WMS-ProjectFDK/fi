<?php
session_start();
ini_set('max_execution_time', -1);
include("../../connect/conn.php");

$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
$level_no = isset($_REQUEST['level_no']) ? strval($_REQUEST['level_no']) : '';


// OPERATION_DATE,UPPER_ITEM_NO,LOWER_ITEM_NO,LEVEL_NO,REVISION,LINE_NO,REFERENCE_NUMBER,QUANTITY,QUANTITY_BASE,FAILURE_RATE,USER_SUPPLY_FLAG,SUBCON_SUPPLY_FLAG,REMARK
$sql  = " select 
s.LOWER_ITEM_NO,
i.ITEM,
i.[DESCRIPTION],
s.LEVEL_NO,
s.QUANTITY_BASE,
cast(s.QUANTITY as decimal(18,2)) QUANTITY,
s.FAILURE_RATE,
s.OPERATION_DATE as input_date ,
s.LOWER_ITEM_NO as ITEM_NO,
s.LINE_NO
from STRUCTURE s 
inner join item i 
on s.LOWER_ITEM_NO = i.ITEM_NO
where s.upper_item_no = '$item_no' and s.level_no = '$level_no'
order by cast(s.line_no as int)" ;
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
