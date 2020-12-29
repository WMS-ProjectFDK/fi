<?php
include("../../../connect/conn.php");

function clean($string) {
    $res = str_ireplace( array( '\'', '"', ',' , ';', '<', '>' ), ' ', $string); 
    return $res;
}

// ?date_awal=2020-12-01
// &date_akhir=2020-12-17
// &ck_date=false
// &cmb_gr_no=
// &ck_gr_no=true
// &cmb_supp=
// &nm_supp=
// &ck_supp=true
// &cmb_po=
// &ck_po=true
// &cmb_item=
// &ck_item=true

$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
$cmb_gr_no = isset($_REQUEST['cmb_gr_no']) ? strval($_REQUEST['cmb_gr_no']) : '';
$ck_gr_no = isset($_REQUEST['ck_gr_no']) ? strval($_REQUEST['ck_gr_no']) : '';
$cmb_supp = isset($_REQUEST['cmb_supp']) ? strval($_REQUEST['cmb_supp']) : '';
$nm_supp = isset($_REQUEST['nm_supp']) ? strval($_REQUEST['nm_supp']) : '';
$ck_supp = isset($_REQUEST['ck_supp']) ? strval($_REQUEST['ck_supp']) : '';
$cmb_po = isset($_REQUEST['cmb_po']) ? strval($_REQUEST['cmb_po']) : '';
$ck_po = isset($_REQUEST['ck_po']) ? strval($_REQUEST['ck_po']) : '';
$cmb_item = isset($_REQUEST['cmb_item']) ? strval($_REQUEST['cmb_item']) : '';
$ck_item = isset($_REQUEST['ck_item']) ? strval($_REQUEST['ck_item']) : '';

if ($ck_date != "true"){
    $date_po = "b.GR_DATE between '$date_awal' and '$date_akhir' and ";
}else{
    $date_po = "";
}   

if ($ck_gr_no != "true"){
    $gr = "b.gr_no = '$cmb_gr_no' and ";
}else{
    $gr = "";
}

if ($ck_supp != "true"){
    $supp = "b.supplier_code = '$cmb_supp' and ";
}else{
    $supp = "";
}

if ($ck_item != "true"){
    $item_no = "a.item_no='$cmb_item' and ";
}else{
    $item_no = "";
}

if ($ck_po != "true"){
    $po = "a.po_no='$cmb_po' and ";
}else{
    $po = "";
}   

$where ="where $date_po $gr $supp $item_no $po a.item_no is not null";

$qry = "select b.SJ_NO, b.gr_no, CAST(b.GR_DATE as varchar(11)) as GR_DATE, b.GR_TYPE, a.PO_NO, b.SUPPLIER_CODE, c.COMPANY,
    a.ITEM_NO, it.DESCRIPTION, un.UNIT, a.QTY, 
    case when c.COUNTRY_CODE = 118 then 'LOKAL' else '' end as tipe_pemb, b.PURCHASE_TYPE
    from SP_GR_DETAILS a
    inner join SP_GR_HEADER b on a.GR_NO =  b.GR_NO
    left join SP_COMPANY c on b.SUPPLIER_CODE = c.COMPANY_CODE
    left join sp_item it on a.ITEM_NO = it.ITEM_NO
    left join sp_unit un on a.UOM_Q = un.UNIT_CODE
    $where
    order by GR_DATE asc";
// echo $qry;
$head = "SJ NO., GR NO., GR DATE, FROM, PO NO., SUPPLIER CODE, SUPPLIER, ITEM NO, DESCRIPTION, UNIT, QTY, tipe_pemb, PURCHASE TYPE";

$out  = 'TRM_BRG_ITM';
$out .= "\n";
$out .= $head;
$out .= "\n";
$results = sqlsrv_query($connect, strtoupper($qry));

while ($l = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {    
    foreach($l AS $key => $value){
        $pos = strpos(strval($value), '"');
        if ($pos !== false) {
            $value = str_replace('"', '\"', $value);
        }
        $out .= clean($value).",";
    }
    $out .= "\n";    
}

header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=trm_brg_itm.csv");
echo $out;
?>