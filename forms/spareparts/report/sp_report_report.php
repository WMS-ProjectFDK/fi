<?php
include("../../../connect/conn.php");
$period = isset($_REQUEST['period']) ? strval($_REQUEST['period']) : '';
$jns_report = isset($_REQUEST['jns_report']) ? strval($_REQUEST['jns_report']) : '';

function clean($string) {
    $res = str_ireplace( array( '\'', '"', ',' , ';', '<', '>' ), ' ', $string);
    return $res;
}

if ($jns_report == 'AK_SALDO_STK'){
    $sql = "select a.item_no, replace(replace(b.DESCRIPTION_ORG,'&nbsp;',''),'%[^0-9a-zA-Z]%','-') as description, isnull(a.qty,0) as qty, a.amount
        from rpt_inventory_Stock a
        left outer join sp_item b on a.item_no = b.ITEM_NO
        order by a.item_no asc";
    $head = "ITEM NO, ITEM NAME, QTY, VALUE";
}elseif ($jns_report == 'AK_TRM_BRG') {
    $sql = "select a.gr_no, CAST(a.gr_date as varchar(10)) as gr_date, a.do_number, a.po_number, a.po_number as inv_no,
        a.supplier_code, b.COMPANY, a.item_no, c.DESCRIPTION_ORG as DESCRIPTION, d.UNIT,
        a.quantity, a.price, a.amount, e.CURR_MARK, a.ex_rate, a.accpac_code
        from rpt_incoming_stock a
        inner join SP_COMPANY b on a.supplier_code = b.COMPANY_CODE
        inner join SP_ITEM c on a.item_no = c.ITEM_NO
        inner join SP_UNIT d on c.UOM_Q=d.UNIT_CODE
        inner join CURRENCY e on a.currency = e.CURR_CODE
        where a.account_month = '$period'
        order by a.supplier_code asc";
    $head = "GR NO, GR DATE, SJ NO., PO NO., INV NO., SUPP CODE, COMPANY, ITEM NO., DESCRIPTION, UNIT, QTY, PRICE, AMOUNT, CURRENCY, KURS, ACCPAC CODE";
}elseif ($jns_report == 'MONTH_AK_KARTU_STOCK'){
    $sql = "select a.item_no, b.DESCRIPTION_ORG as description, b.REPORTGROUP_CODE,cast(a.trans_date AS VARCHAR(10)) AS trans_date,
        a.remark, a.doc_no, a.po_no, a.do_no, a.quantity, a.amount
        from RPT_STOCK_CARD a
        inner join SP_ITEM b on a.item_no=b.ITEM_NO
        where a.account_month = '$period'
        order by a.ITEM_NO, a.TRANS_DATE";
    $head = "ITEM NO., DESCRIPTION, KD REPORT, TRANS. DATE, REMARK, DOC NO., PO NO., DO NO., QTY, AMOUNT";
}elseif ($jns_report == 'MONTH_AK_PAKAI_BRG'){
    $sql = "select a.item_no, b.DESCRIPTION_ORG as DESCRIPTION, CAST(a.slip_date as varchar(10)) as slip_date, 
        mtd.COST_PROCESS_CODE, e.COST_SUBJECT_NAME, a.dept_name, a.section_code, a.trans_name,
        c.COST_PROCESS_NAME, a.quantity, a.price, a.amount, f.CURR_MARK, a.ex_rate
        FROM RPT_MATERIAL_TRANSACTION a
        left join SP_MTE_DETAILS mtd on a.trans_code = mtd.SLIP_NO
        inner join SP_ITEM b on a.item_no=b.ITEM_NO
        left join SP_COSTPROCESS c on a.trans_name = c.COST_PROCESS_CODE
        left join SP_COSTPROCESS_SECTION d on c.CP_SECTION_CODE = d.CP_SECTION_CODE
        left join SP_COSTSUBJECT e on mtd.COST_PROCESS_CODE = e.cost_subject_code
        left join CURRENCY f on a.currency = f.CURR_CODE
        where a.account_month = '$period'
        order by a.slip_date";
    $head = "ITEM NO., DESCRIPTION, SLIP DATE, KODE TRANS., NAMA TRANS., KODE DEPT., NAMA DEPT., KODE BAGIAN, NAME BAGIAN, QTY, PRICE, AMOUNT, UNIT, KURS";
}elseif ($jns_report == 'MONTH_AK_PAKAI_SPAREPART'){
    $sql = "select a.reportgroup_code, a.cost_process_code, b.COST_PROCESS_NAME, a.used_quantity, a.return_quantity, a.used_amount
        FROM RPT_MT_TRANSACTION_COST_PROCESS a
        INNER JOIN SP_COSTPROCESS b on a.cost_process_code = b.COST_PROCESS_CODE
        where a.account_month = '$period'
        order by a.reportgroup_code, a.cost_process_code";
    $head = "GRP ITEM, KODE DEPT, NAME DEPT, USED QTY, RETURN QTY, AMOUNT USED";
}elseif ($jns_report == 'MONTH_AK_REKAPSTK_DETAIL'){
    $sql = "select b.REPORTGROUP_CODE, a.item_no, b.DESCRIPTION_ORG as DESCRIPTION, a.this_inventory, a.this_inventory_value,
        a.incoming_quantiy, a.incoming_value, a.return_quantity, a.return_value, a.outgoing_quantiy, a.outgoing_value,
        a.last_inventory, b.MACHINE_CODE
        FROM RPT_STOCK_DETAIL a
        inner join SP_ITEM b on a.item_no = b.ITEM_NO
        where a.account_month = '$period'
        order by a.item_no ";
    $head = "GRP ITEM, ITEM NO., DESCRIPTION, QTY AWAL, AMOUNT AWAL, QTY BELI, VALUE BELI, QTY RETURN, VALUE RETURN, QTY OUTGOING, AMOUNT OUTGOING, LAST QTY, MACHINE";
}elseif ($jns_report == 'MONTH_AK_TRM_BRG'){
    $sql = "select distinct a.item_no, b.DESCRIPTION_ORG as DESCRIPTION, cast(a.gr_date as varchar(10)) as gr_date, a.supplier_code, c.COMPANY,
        sum(a.quantity) as quantity, sum(a.price) as price, sum(a.quantity) *sum(a.price) as total, 
        d.CURR_MARK, a.ex_rate, sum(a.quantity) * sum(a.price) * a.ex_rate as total_usd,
        a.gr_no, a.accpac_code
        from rpt_incoming_stock a
        inner join SP_ITEM b on a.item_no = b.ITEM_NO
        inner join SP_COMPANY c on a.supplier_code = c.COMPANY_CODE
        inner join CURRENCY d on a.currency = d.CURR_CODE
        where a.account_month = '$period'
        group by a.item_no, b.DESCRIPTION, a.gr_date, a.supplier_code, c.COMPANY, d.CURR_MARK, a.ex_rate, a.gr_no, a.accpac_code
        order by a.supplier_code, a.item_no";
    $head = "ITEM NO., DESCRIPTION, DATE, SUPPLIER CODE, SUPPLIER NAME, QTY, PRICE, TOTAL, CURRENCY, KURS, TOTAL USD, INV NO, ACCPAC CODE";
}elseif ($jns_report == 'REKAP_STOCK_SPART'){
    $sql = "select distinct MACHINE_CODE, sum(this_inventory) as this_inventory, sum(this_inventory_value) as this_inventory_value,
        sum(incoming_quantiy) incoming_quantiy, sum(incoming_value) incoming_value,
        sum(outgoing_quantiy) outgoing_quantiy, sum(outgoing_value) outgoing_value,
        sum(return_quantity) return_quantity, sum(return_value) return_value,
        sum(last_inventory) last_inventory
        FROM RPT_STOCK_DETAIL
        where account_month = '$period'
        group by MACHINE_CODE
        order by MACHINE_CODE";
    $head = "GROUP PART, THIS INV. QTY, THIS INV. AMOUNT, INCOMING QTY, INCOMING AMOUNT, OUTGOING QTY, OUTGOING AMOUNT, RETURN QTY, RETURN AMOUNT, LAST INV.";
}

$out  = $jns_report;
$out .= "\n";
$out .= $period;    
$out .= "\n";
$out .= $head;
$out .= "\n";
$results = sqlsrv_query($connect, strtoupper($sql));

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

if ($jns_report == 'AK_SALDO_STK'){
    header("Content-type: text/x-csv");
    header("Content-Disposition: attachment; filename=AK_SALDO_STK.csv");
}elseif ($jns_report == 'AK_TRM_BRG') {
    header("Content-type: text/x-csv");
    header("Content-Disposition: attachment; filename=AK_TRM_BRG.csv");
}elseif ($jns_report == 'MONTH_AK_KARTU_STOCK') {
    header("Content-type: text/x-csv");
    header("Content-Disposition: attachment; filename=MONTH_AK_KARTU_STOCK.csv");
}elseif ($jns_report == 'MONTH_AK_PAKAI_BRG') {
    header("Content-type: text/x-csv");
    header("Content-Disposition: attachment; filename=MONTH_AK_PAKAI_BRG.csv");
}elseif ($jns_report == 'MONTH_AK_PAKAI_SPAREPART') {
    header("Content-type: text/x-csv");
    header("Content-Disposition: attachment; filename=MONTH_AK_PAKAI_SPAREPART.csv");
}elseif ($jns_report == 'MONTH_AK_REKAPSTK_DETAIL') {
    header("Content-type: text/x-csv");
    header("Content-Disposition: attachment; filename=MONTH_AK_REKAPSTK_DETAIL.csv");
}elseif ($jns_report == 'MONTH_AK_TRM_BRG') {
    header("Content-type: text/x-csv");
    header("Content-Disposition: attachment; filename=MONTH_AK_TRM_BRG.csv");
}elseif ($jns_report == 'REKAP_STOCK_SPART') {
    header("Content-type: text/x-csv");
    header("Content-Disposition: attachment; filename=REKAP_STOCK_SPART.csv");
}
echo $out;
?>