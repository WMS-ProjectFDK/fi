<?php
session_start();
ini_set('max_execution_time', -1);
include("../../../connect/conn.php");

$cmb_week = isset($_REQUEST['cmb_week']) ? strval($_REQUEST['cmb_week']) : '';
$ck_week = isset($_REQUEST['ck_week']) ? strval($_REQUEST['ck_week']) : '';
$cmb_fg = isset($_REQUEST['cmb_fg']) ? strval($_REQUEST['cmb_fg']) : '';
$ck_fg = isset($_REQUEST['ck_fg']) ? strval($_REQUEST['ck_fg']) : '';
$cmb_item = isset($_REQUEST['cmb_item']) ? strval($_REQUEST['cmb_item']) : '';
$ck_item = isset($_REQUEST['ck_item']) ? strval($_REQUEST['ck_item']) : '';
$itemNya = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
$levelNya = isset($_REQUEST['level']) ? strval($_REQUEST['level']) : '';

if ($ck_week != "true"){
	$value = '';
	$vBal = '';		$nBal = '+BALANCE as NBAL_';
	$r = '(';
	$n = 'N_';
	$jumN = $cmb_week * 7 ;
	for ($i=1; $i <= $jumN ; $i++) { 
		$value .= $n.$i.',';
		$vBal .= $n.$i.$nBal.$i.',';
		if ($i == $jumN){
			$r .= $n.$i.'< 0) and ';
		}else{
			$r .= $n.$i.'< 0 OR ';
		}
	}
}else{
	$value = '';
	$vBal = '';
	$n = 'N_';		$nBal = '+BALANCE as NBAL_';
	for ($i=1; $i <= 90 ; $i++) { 
		$value .= $n.$i.',';
		$vBal .= $n.$i.$nBal.$i.',';
	}
	$r = '';
}

if ($ck_fg != "true"){
	$fg = "aa.item_no in (select distinct (lower_item_no) from structure where upper_item_no=$cmb_fg
		and level_no= $levelNya) and ";
}else{
	$fg = " ";
}

if($ck_item != "true") {
	$item = "aa.item_no = $cmb_item and ";
}else{
	$item = " ";
}

$where = "where $fg $item $r 
	aa.item_no in (select distinct case when lower_item_no > 70000000 then lower_item_no - 70000000 else lower_item_no end lower_item_no from structure where upper_item_no=$itemNya
				and level_no = $levelNya
			   ) and no_id = 4 ";

$cek = "select no_id, description, $value aaa.item_no, item_desc, $vBal balance from (
		select no_id, cc.description + ' ( ' + cast(bb.this_inventory as nvarchar(20)) + ' )' description, $value  
			   aa.item_no, 
			   item_desc, 
			   (select isnull(sum(bal_qty),0) as balance from po_details where item_no= aa.item_no and eta <= cast(getdate() as date) and bal_qty != 0) as balance 
		from ztb_mrp_data_pck aa 
		left outer join whinventory bb 
			on aa.item_no = bb.item_no 
		inner join item cc 
			on aa.item_no = cc.item_no 
			$where)aaa order by aaa.item_no, description" ;
$data_cek = sqlsrv_query($connect, strtoupper($cek));

// echo $cek;

$items = array();
$rowno=0;

while($row = sqlsrv_fetch_object($data_cek)){
	array_push($items, $row);
	$BAL = $items[$rowno]->BALANCE;
	$items[$rowno]->BALANCE = number_format($BAL);
	//echo $items[$rowno]->N_1.'<br/>';
	//ueng update 26-4-19
	if ($ck_week != "true"){
		for ($h=1; $h <= $jumN ; $h++) {
			$n0 = 'N_'.$h;
			$n_val = $items[$rowno]->$n0;
			$n_valNya = $n_val;

			$b0 = 'NBAL_'.$h;
			$b_val = $items[$rowno]->$b0;
			$b_valNya = $b_val;

			if($n_valNya <= 0 && $b_valNya<= 0){
				$items[$rowno]->$n0 = '<span style="text-decoration: none; color: #FF0000;">('.number_format($n_valNya,2).')</span>';
				$items[$rowno]->$b0 = '<span style="text-decoration: none; color: #FF0000;">('.number_format($b_valNya,2).')</span>';
			}elseif($n_valNya <= 0 && $b_valNya > 0){
				$items[$rowno]->$n0 = '<span style="text-decoration: none; color: #FFC000;">('.number_format($n_valNya,2).')</span>';
				$items[$rowno]->$b0 = '<span style="text-decoration: none; color: #FFC000;">('.number_format($b_valNya,2).')</span>';
			}else{
				$items[$rowno]->$n0 = number_format($n_valNya,2);
				$items[$rowno]->$b0 = number_format($b_valNya,2);
			}
		}
		
	}else{
		for ($h=1; $h <= 90 ; $h++) {
			$n0 = 'N_'.$h;
			$n_val = $items[$rowno]->$n0;
			$n_valNya = $n_val;

			$b0 = 'NBAL_'.$h;
			$b_val = $items[$rowno]->$b0;
			$b_valNya = $b_val;

			if($n_valNya <= 0 && $b_valNya<= 0){
				$items[$rowno]->$n0 = '<span style="text-decoration: none; color: #FF0000;">('.number_format($n_valNya,2).')</span>';
				$items[$rowno]->$b0 = '<span style="text-decoration: none; color: #FF0000;">('.number_format($b_valNya,2).')</span>';
			}elseif($n_valNya <= 0 && $b_valNya > 0){
				$items[$rowno]->$n0 = '<span style="text-decoration: none; color: #FFC000;">('.number_format($n_valNya,2).')</span>';
				$items[$rowno]->$b0 = '<span style="text-decoration: none; color: #FFC000;">('.number_format($b_valNya,2).')</span>';
			}else{
				$items[$rowno]->$n0 = number_format($n_valNya,2);
				$items[$rowno]->$b0 = number_format($b_valNya,2);
			}
		}
	}
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>