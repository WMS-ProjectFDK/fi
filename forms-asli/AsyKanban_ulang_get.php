<?php
session_start();
include("../connect/conn.php");

$date_prod = isset($_REQUEST['date_prod']) ? strval($_REQUEST['date_prod']) : '';
$date_prod_z = isset($_REQUEST['date_prod_z']) ? strval($_REQUEST['date_prod_z']) : '';
$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
$Line = isset($_REQUEST['Line']) ? strval($_REQUEST['Line']) : '';
$ck_assy_line = isset($_REQUEST['ck_assy_line']) ? strval($_REQUEST['ck_assy_line']) : '';
$cell_type = isset($_REQUEST['cell_type']) ? strval($_REQUEST['cell_type']) : '';
$ck_cell_type = isset($_REQUEST['ck_cell_type']) ? strval($_REQUEST['ck_cell_type']) : '';
$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';
$lineA = str_replace("-", "#", $line);

if ($ck_date != "true"){
	$dt_prod = "to_char(a.date_prod,'yyyy-mm-dd') between '$date_prod' and '$date_prod_z' and ";
}else{
	$dt_prod = " ";
}

if ($ck_assy_line != "true"){
	$assy_ln = "replace(a.asyline,'#','-') = '$Line' and ";
}else{
	$assy_ln = " ";
}

if ($ck_cell_type != "true"){
	$cell = "a.cell_type = '$cell_type' and ";
}else{
	$cell = " ";
}


if ($src !='') {
	$where = "where id like '%$src%'";
}else{
	$where = "where $cell $dt_prod $assy_ln a.asyline is not null";
}

$sql_h = "select * from (select a.* from ztb_assy_print a $where 
	order by a.date_prod desc) where rownum <= 150";
$dt = oci_parse($connect, $sql_h);
oci_execute($dt);

$items = array();
$rowno=0;

while ($data=oci_fetch_object($dt)){
	array_push($items, $data);
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>