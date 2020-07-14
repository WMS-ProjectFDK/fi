<?php
session_start();
ini_set('max_execution_time', -1);
include("../connect/conn.php");


    $date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_cr_date = isset($_REQUEST['ck_cr_date']) ? strval($_REQUEST['ck_cr_date']) : '';
	$cmb_wo_no = isset($_REQUEST['cmb_wo_no']) ? strval($_REQUEST['cmb_wo_no']) : '';
	$ck_wo_no = isset($_REQUEST['ck_wo_no']) ? strval($_REQUEST['ck_wo_no']) : '';
	$cmb_po_no = isset($_REQUEST['cmb_po_no']) ? strval($_REQUEST['cmb_po_no']) : '';
	$ck_po_no = isset($_REQUEST['ck_po_no']) ? strval($_REQUEST['ck_po_no']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$flag = 0;

    

	if ($ck_cr_date != "true"){
		$date = " and to_char(cr_date,'yyyy-mm-dd') between '$date_awal' and '$date_akhir'  ";
	}else{
		$date = "";
		$flag+=1;
	}

	if ($ck_wo_no != "true"){
		$prf = " and work_order = '$cmb_wo_no'  ";
	}else{
		$prf = "";
		$flag+=1;
	}

	if ($ck_item_no != "true"){
		$pieces = explode('-', $cmb_item_no);
		$part1 = implode('-', array_slice($pieces, 0, 1));
		$item_no = " and item_no='$part1'  ";
	}else{
		$item_no = "";
		$flag+=1;
	}

	if($ck_po_no!='true'){
		$supp = " and po_no  = '$cmb_po_no'  ";
	}else{
		$supp = "";
		$flag+=1;
    }
	
	if($flag < 4){
		$where =" where 1=1 $date $prf $item_no $supp  ";
	};
    

$cek = "select work_order, 'DETAIL' edit_detail,'HEADER' edit_header,status,date_code,BOM_LEVEL,BOM_EDIT_STAT,
po_no,
po_line_no,
item_no,
item_name, 
cr_date, 
batery_type,
cell_grade,
qty
from mps_header  $where
order by cr_date asc" ;

$data_cek = oci_parse($connect, $cek);
oci_execute($data_cek);


$items = array();
$rowno=0;

while($row = oci_fetch_object($data_cek)){
	array_push($items, $row);
	$q = $items[$rowno]->QTY;
	$i = "'".$items[$rowno]->PO_NO."'";
	$edit = "'".$items[$rowno]->BOM_EDIT_STAT."'";
	$date_code = "'".$items[$rowno]->DATE_CODE."'";
	$item_no = "'".$items[$rowno]->ITEM_NO."'";
	$bom_level = "'".$items[$rowno]->BOM_LEVEL."'";
	$work_order = "'".$items[$rowno]->WORK_ORDER."'";
	
	$status = "'".$items[$rowno]->STATUS."'";
	$cr_date = "'".$items[$rowno]->CR_DATE."'";
	$n = strval($items[$rowno]->PO_LINE_NO);
	$j = "'".$items[$rowno]->WORK_ORDER."'";
	$items[$rowno]->QTY = number_format($q);
	$v = $items[$rowno]->EDIT_HEADER;
	$s = $items[$rowno]->EDIT_DETAIL;
	$items[$rowno]->EDIT_HEADER = '<a href="javascript:void(0)" title="'.$v.'" onclick="editheader('.$i.','.$n.','.$date_code.','.$status.','.$cr_date.','.$q.','.$j.','.$bom_level.','.$item_no.','.$edit.')" style="text-decoration: none; color: blue;">'.$v.'</a>';
	$items[$rowno]->EDIT_DETAIL = '<a href="javascript:void(0)" title="'.$s.'" onclick="editdetail('.$i.','.$n.')" style="text-decoration: none; color: blue;">'.$s.'</a>';
	$items[$rowno]->SPLIT_WO = '<a href="javascript:void(0)" title="'.$s.'" onclick="splitwo('.$work_order.','.$q.','.$i.','.$n.')" style="text-decoration: none; color: blue;">SPLIT</a>';
		
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>