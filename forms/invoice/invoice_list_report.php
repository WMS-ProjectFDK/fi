<?php

$date_start = isset($_REQUEST['date_start']) ? strval($_REQUEST['date_start']) : '';
$date_end = isset($_REQUEST['date_end']) ? strval($_REQUEST['date_end']) : '';


 $sql = "SELECT cast(r.SI_NO as varchar(20)),w.CUSTOMER_PO_NO,
    r.INV_NO,
    s.ITEM_NO,
    m.ITEM,
    cast(a.EX_FACTORY as varchar(10)) EX_FACTORY,
    cast(r.ETA as varchar(10)) ETA,
    cast(r.ETD as varchar(10)) ETD,
    cast(r.BL_DATE as varchar(10)) BL_DATE,
    cc.COMPANY,
    si.CONSIGNEE_NAME,
    si.CONSIGNEE_ADDR1,
    si.NOTIFY_NAME,
    si.EMKL_NAME, 
    si.FORWARDER_NAME,
    si.DISCH_PORT
    FROM DO_HEADER r 
    inner join DO_DETAILS s 
    on r.INV_NO = s.DO_NO
    left join INDICATION a 
    on r.DO_NO = a.INV_NO and s.LINE_NO = a.INV_LINE_NO
    left join SI_HEADER si 
    on r.SI_NO = si.SI_NO
    left join ANSWER w 
    on w.ANSWER_NO = a.ANSWER_NO
    inner join item m 
    on m.ITEM_NO = s.ITEM_NO
    inner join COMPANY cc
    on r.CUSTOMER_CODE = cc.COMPANY_CODE
    where r.inv_date between '$date_start' and '$date_end'";

    include("../../connect/conn.php");

    $out = "SI NO,CUSTOMER PO NO,INVOICE NO,ITEM NUMBER, BRAND NAME, EX FACTORY DATE, ETA,ETD, BL DATE, CUSTOMER NAME,CONSIGNEE NAME, CONSIGNEE ADDRESS, NOTIFY NAME, EMKL NAME, FORWARDER NAME, DISCHARGE PORT";
    $out .= "\n";
    $results = sqlsrv_query($connect, strtoupper($sql));
    while ($l = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
        
        foreach($l AS $key => $value){
            
            $pos = strpos(strval($value), '"');
            if ($pos !== false) {
                $value = str_replace('"', '\"', $value);
            }
            $out .= '"'.$value.'",';
        }
        $out .= "\n";
        
    }
    header("Content-type: text/x-csv");
    header("Content-Disposition: attachment; filename=invoice_list.csv");
    echo $out;
?>