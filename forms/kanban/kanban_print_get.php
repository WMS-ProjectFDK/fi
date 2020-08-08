<?php
error_reporting(0);
session_start();
ini_set('max_execution_time', -1);
include("../../connect/conn.php");


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
		$date = " and cr_date between '$date_awal' and '$date_akhir'  ";
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
		$item_no = " and i.item_no='$part1'  ";
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
    

$cek = "select r.work_order,status,date_code,BOM_LEVEL,BOM_EDIT_STAT,
po_no,
po_line_no,
r.item_no,
r.item_name, 
cast(cr_date as varchar(10)) cr_date, 
batery_type,
cell_grade,
qty,
QTY_PRODUKSI,
        ceiling(r.qty/ pi.pallet_unit_number) totalPallet, 
        pi.pallet_unit_number QTY_PALLET,
        case when (qty - (floor(r.qty/ pi.pallet_unit_number) * pi.pallet_unit_number)) = 0
         	 then pi.pallet_unit_number 
        else (qty - (floor(r.qty/ pi.pallet_unit_number) * pi.pallet_unit_number)) end QtyPltAkhir,
        pi.pallet_ctn_number as qty_carton,
        ceiling((case when (qty - (floor(r.qty/ pi.pallet_unit_number) * pi.pallet_unit_number)) = 0
         		   then pi.pallet_unit_number 
          	  else (qty - (floor(r.qty/ pi.pallet_unit_number) * pi.pallet_unit_number))
          	  end) 
          	  / (pi.pallet_unit_number/pi.pallet_ctn_number)
            ) as qty_carton_akhir,
        pi.pallet_unit_number/pi.pallet_ctn_number as qty_pcspercarton, com.country_code, 
        case when amz.jum_wo is null then 0 else amz.jum_wo end as jum_sscc
from mps_header r
inner join item i on r.item_no = i.item_no
inner join packing_information pi on pi.pi_no = i.pi_no  
left outer join (select wo_no,
			sum(case when slip_type = 80 then slip_quantity else slip_quantity*-1 end) QTY_PRODUKSI 
			from production_income group by wo_no) pix on r.work_order = pix.wo_no 
left outer join (select distinct a.customer_po_no, b.country_code from so_header a 
                 inner join company b on a.consignee_code = b.company) com on r.po_no = com.customer_po_no
left outer join (select wo, isnull(count(*),0) as jum_wo from ztb_amazon_wo group by wo) amz on r.work_order = amz.wo

order by cr_date asc" ;

$data_cek = sqlsrv_query($connect, strtoupper($cek));



$items = array();
$rowno=0;

while($row = sqlsrv_fetch_object($data_cek)){
	array_push($items, $row);
	
	$q = $items[$rowno]->QTY;
	$i = "'".$items[$rowno]->PO_NO."'";
	$edit = "'".$items[$rowno]->BOM_EDIT_STAT."'";
	$date_code = "'".$items[$rowno]->DATE_CODE."'";
	$item_no = "'".$items[$rowno]->ITEM_NO."'";
	$bom_level = "'".$items[$rowno]->BOM_LEVEL."'";
	
	$status = "'".$items[$rowno]->STATUS."'";
	$cr_date = "'".$items[$rowno]->CR_DATE."'";
	$n = strval($items[$rowno]->PO_LINE_NO);
	$j = "'".$items[$rowno]->WORK_ORDER."'";
	$items[$rowno]->QTY = number_format($q);
	$v = $items[$rowno]->EDIT_HEADER;
	$s = $items[$rowno]->EDIT_DETAIL;
	$items[$rowno]->EDIT_HEADER = '<a href="javascript:void(0)" title="'.$v.'" onclick="editheader('.$i.','.$n.','.$date_code.','.$status.','.$cr_date.','.$q.','.$j.','.$bom_level.','.$item_no.','.$edit.')" style="text-decoration: none; color: blue;">'.$v.'</a>';
	$items[$rowno]->EDIT_DETAIL = '<a href="javascript:void(0)" title="'.$s.'" onclick="editdetail('.$i.','.$n.')" style="text-decoration: none; color: blue;">'.$s.'</a>';
	$e = $items[$rowno]->QTY_PRODUKSI;
	$w = "'".$items[$rowno]->WORK_ORDER."'";
	$items[$rowno]->QTY_PRODUKSI = '<a href="javascript:void(0)" title="'.$e.'" onclick="info_kuraire('.$w.')"  style="text-decoration: none; color: blue;">'.number_format($e).'</a>';
	
	$rowno++;
}
$result["rows"] = $items;
echo json_encode($result);
?>