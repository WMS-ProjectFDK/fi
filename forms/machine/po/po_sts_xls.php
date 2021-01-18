<?php
include("../../../connect/conn.php");

function clean($string) {
    $res = str_ireplace( array( '\'', '"', ',' , ';', '<', '>' ), ' ', $string); 
    return $res;
}

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
$ck_eta = isset($_REQUEST['ck_eta']) ? strval($_REQUEST['ck_eta']) : '';

if ($ck_date != "true"){
    $date_po = "b.PO_DATE between '$date_awal' and '$date_akhir' and ";
}else{
    $date_po = "";
}   

if ($ck_supplier != "true"){
    $supp = "b.supplier_code = '$supplier' and ";
}else{
    $supp = "";
}

if ($ck_item_no != "true"){
    $item_no = "a.item_no='$cmb_item_no' and ";
}else{
    $item_no = "";
}

if ($ck_po != "true"){
    $po = "a.po_no='$cmb_po' and ";
}else{
    $po = "";
}   

if ($ck_eta != "true"){
    $eta = "a.eta = '$date_eta' and ";
}else{
    $eta = "";
}

$where ="where $supp $item_no $po $eta $date_po a.item_no is not null";

$qry = "select a.po_no, CAST(b.PO_DATE as varchar(11)) as PO_DATE, 
    c1.COMPANY, c2.COMPANY as SHIP_TO, b.ATTN,
    curr.CURR_SHORT, b.EX_RATE, b.TTERM, c1.PDAYS, c1.PDESC,
    case when b.TRANSPORT=1 then 'AIR'
        when b.transport=2 then 'OCEAN'
        when b.transport=3 then 'TRUCK'
    end as transport,
    b.REMARK1, a.LINE_NO, a.ITEM_NO, it.DESCRIPTION, un.UNIT, CAST(a.ETA as varchar(11)) as ETA, 
    a.QTY, a.BAL_QTY, a.U_PRICE, a.AMT_O, a.AMT_L, a.MPR_NO
    from SP_PO_DETAILS a
    inner join SP_PO_HEADER b on a.PO_NO = b.PO_NO
    left join SP_COMPANY c1 on b.SUPPLIER_CODE = c1.COMPANY_CODE
    left join SP_COMPANY c2 on b.SHIPTO_CODE = c2.COMPANY_CODE
    left join CURRENCY curr on b.CURR_CODE = curr.CURR_CODE
    inner join SP_ITEM it on a.ITEM_NO = it.ITEM_NO
    left join SP_UNIT un on a.UOM_Q = un.UNIT_CODE
    $where
    order by b.PO_DATE, a.po_no asc, a.line_no";
// echo $qry;
$head = "PO NO, PO DATE, COMPANY, SHIP TO, ATTN, CURR_SHORT, EX_RATE, TRADE TERM, TERM OF DAYS, TERM OF PAYMENT, TRANSPORT, KETERANGAN, LINE, ITEM_NO, DESCRIPTION, SATUAN, ETA, QTY, BAL QTY, PRICE, AMT ORI, AMT LOC, MPR_NO";

$out  = 'po_brg';
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
header("Content-Disposition: attachment; filename=po_brg.csv");
echo $out;
?>