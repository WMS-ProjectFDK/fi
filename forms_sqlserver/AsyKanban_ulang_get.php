<?php
session_start();
include("../connect/conn_kanbansys.php");

$date_prod = isset($_REQUEST['date_prod']) ? strval($_REQUEST['date_prod']) : '';
$cell_type = isset($_REQUEST['cell_type']) ? strval($_REQUEST['cell_type']) : '';
$Line = isset($_REQUEST['Line']) ? strval($_REQUEST['Line']) : '';

if ($cell_type != ''){
	$cell = "a.cell_type = '$cell_type' and ";
}else{
	$cell = " ";
}

if ($date_prod != ''){
	$dt_prod = "CONVERT(CHAR(10),a.date_prod,121) = '$date_prod' and ";
}else{
	$dt_prod = " ";
}

if ($Line != ''){
	$assy_ln = "replace(a.asyline,'#','-') = '$Line' and ";
}else{
	$assy_ln = " ";
}

$where = "where $cell $dt_prod $assy_ln a.asyline is not null ";

$sql_h = "select TOP 150 ASYLINE, CELL_TYPE, DATE_PROD, PALLET, QTY, ID, PRINTED, ID_PLAN, BOX, UPTO_DATE
	from ztb_assy_print a 
	$where
	order by a.date_prod desc";
$dt = odbc_exec($connect, $sql_h);
$items = array();
$rowno=0;

while ($data = odbc_fetch_object($dt)){
	array_push($items, $data);
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>