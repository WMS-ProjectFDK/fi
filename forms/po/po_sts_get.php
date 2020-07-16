<?php
ini_set('memory_limit', '-1');
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../../connect/conn.php");

$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
$supplier = isset($_REQUEST['supplier']) ? strval($_REQUEST['supplier']) : '';
$ck_supplier = isset($_REQUEST['ck_supplier']) ? strval($_REQUEST['ck_supplier']) : '';
$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
$cmb_po = isset($_REQUEST['cmb_po']) ? strval($_REQUEST['cmb_po']) : '';
$ck_po = isset($_REQUEST['ck_po']) ? strval($_REQUEST['ck_po']) : '';
$date_eta = isset($_REQUEST['date_eta']) ? strval($_REQUEST['date_eta']) : '';
$date_eta_akhir = isset($_REQUEST['date_eta_akhir']) ? strval($_REQUEST['date_eta_akhir']) : '';
$ck_eta = isset($_REQUEST['ck_eta']) ? strval($_REQUEST['ck_eta']) : '';
$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';
$gr = isset($_REQUEST['gr']) ? strval($_REQUEST['gr']) : '';
$ck_gr = isset($_REQUEST['ck_gr']) ? strval($_REQUEST['ck_gr']) : '';
$ck_endorder = isset($_REQUEST['ck_endorder']) ? strval($_REQUEST['ck_endorder']) : '';
$ck_endorder_str = "";
$prf = isset($_REQUEST['prf']) ? strval($_REQUEST['prf']) : '';
$ck_prf = isset($_REQUEST['ck_PRF']) ? strval($_REQUEST['ck_PRF']) : '';

if ($ck_date != "true"){
    $date_po = "CAST(h.po_date as varchar(10)) between '$date_awal' and '$date_akhir' and ";
}else{
    $date_po = "";
}   

if ($ck_gr != "true"){
    $gr = "gg.gr_no = '$gr' and ";
}else{
    $gr = "";
}

if ($ck_supplier != "true"){
    $supp = "h.supplier_code = '$supplier' and ";
}else{
    $supp = "";
}

if ($ck_prf != "true"){
    $prf = "d.prf_no = '$prf' and ";
}else{
    $prf = "";
}

if ($ck_item_no != "true"){
    $item_no = "d.item_no='$cmb_item_no' and ";
}else{
    $item_no = "";
}

if ($ck_po != "true"){
    $po = "d.po_no='$cmb_po' and ";
}else{
    $po = "";
}   

if ($ck_eta != "true"){
    $eta = "d.eta between CAST('$date_eta' as varchar(10)) and CAST('$date_eta_akhir' as varchar(10)) and ";
}else{
    $eta = "";
}

if ($ck_endorder != "true"){
    $ck_endorder_str = " d.bal_qty > 0 and ";
}else{
    $ck_endorder_str = "    ";
}

$where ="where $date_po $supp $item_no $po $eta $ck_endorder_str $prf  $gr d.item_no is not null";

$qry = "select curr_mark,gg.gr_no,h.supplier_code,company, d.po_no, h.po_date, d.item_no, itm.description, line_no,d.eta, d.qty, d.gr_qty,gg.qty as Receipt_Qty, 
    gg.gr_Date, c.accpac_company_code, d.eta etad, gg.gr_Date grd, 
    DATEDIFF(day,d.eta,gg.gr_date) as diff, d.u_price,itm.standard_price,d.amt_o, d.amt_l,d.bal_qty from po_header h
    left join po_details d on h.po_no = d.po_no
    left join company c on h.supplier_code = c.company_code and c.company_type = 3
    left join currency bx on h.curr_code = bx.curr_code
    left outer join (select gh.gr_no,gr_date, gs.po_no, gs.po_line_no, gs.qty from gr_details gs left join gr_header gh on gs.gr_no = gh.gr_no) gg
    on gg.po_no = d.po_no and gg.po_line_no = d.line_no
    left join item itm on d.item_no= itm.item_no
    $where 
    order by h.po_Date,h.po_no asc, line_no,gg.gr_Date asc";

$result = sqlsrv_query($connect, strtoupper($qry));

$items = array();
    $rowno=0;
    while($row = sqlsrv_fetch_object($result)){
        array_push($items, $row);
           $items[$rowno]->GR_QTY = number_format($items[$rowno]->GR_QTY);
           $items[$rowno]->QTY = number_format($items[$rowno]->QTY);
           $items[$rowno]->AMT_O = number_format($items[$rowno]->AMT_O,2);
           $items[$rowno]->AMT_L = number_format($items[$rowno]->AMT_L,2);
           $items[$rowno]->U_PRICE = number_format($items[$rowno]->U_PRICE,6);
           $items[$rowno]->STANDARD_PRICE = number_format($items[$rowno]->STANDARD_PRICE,2);
           $items[$rowno]->RECEIPT_QTY = number_format($items[$rowno]->RECEIPT_QTY);
           $items[$rowno]->BAL_QTY = number_format($items[$rowno]->BAL_QTY);
        $rowno++;
    }
    $result1["rows"] = $items;
    echo json_encode($result1);
?>