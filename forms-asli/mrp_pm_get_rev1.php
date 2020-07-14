<?php
session_start();
ini_set('max_execution_time', -1);
include("../connect/conn.php");

$cmb_week = isset($_REQUEST['cmb_week']) ? strval($_REQUEST['cmb_week']) : '';
$ck_week = isset($_REQUEST['ck_week']) ? strval($_REQUEST['ck_week']) : '';
$cmb_fg = isset($_REQUEST['cmb_fg']) ? strval($_REQUEST['cmb_fg']) : '';
$ck_fg = isset($_REQUEST['ck_fg']) ? strval($_REQUEST['ck_fg']) : '';
$cmb_item = isset($_REQUEST['cmb_item']) ? strval($_REQUEST['cmb_item']) : '';
$ck_item = isset($_REQUEST['ck_item']) ? strval($_REQUEST['ck_item']) : '';

if ($ck_week != "true"){
	$value = '';
	$r = '(';
	$n = 'N_';
	$jumN = $cmb_week * 7 ;
	for ($i=1; $i <= $jumN ; $i++) { 
		$value .= $n.$i.',';
		if ($i == $jumN){
			$r .= $n.$i.'< 0) and ';
		}else{
			$r .= $n.$i.'< 0 OR ';
		}
	}
}else{
	$n = 'N_';
	for ($i=1; $i <= 90 ; $i++) { 
		$value .= $n.$i.',';
	}
	$result = '';
}

if ($ck_fg != "true"){
	$fg = "aa.item_no in (select distinct (lower_item_no) from structure where upper_item_no=$cmb_fg
		and level_no= (select max(level_no) from structure where upper_item_no=$cmb_fg)) and ";
}else{
	$fg = " ";
}

if($ck_item != "true") {
	$item = "aa.item_no = $cmb_item and ";
}else{
	$item = " ";
}

$where = "where $fg $item $r no_id = 4 ";
$cek = "select no_id, cc.description || '(' || bb.this_inventory || ')' description, $value item_no, item_desc from ztb_mrp_data_pck aa inner join whinventory bb on aa.item_no = bb.item_no inner join item cc on aa.item_no = cc.item_no  $where" ;
$data_cek = oci_parse($connect, $cek);
oci_execute($data_cek);

$items = array();
$rowno=0;

while($row = oci_fetch_object($data_cek)){
	array_push($items, $row);
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>