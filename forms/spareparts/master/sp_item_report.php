<?php
    include("../../../connect/conn.php");

    $sql = "select a.item_no as kode_barang, a.DESCRIPTION as nama_barang, a.SPECIFICATION, a.MACHINE_CODE, a.ITEM_TYPE1, d.ITEM_TYPE, d.ITEM_TYPE_NAME,
        a.UOM_Q, e.UNIT, a.REPORTGROUP_CODE, f.REPORTGROUP_NAME, a.CLASS_CODE, c.CLASS_1, a.CURR_CODE, g.CURR_SHORT, 
        a.STOCK_SUBJECT_CODE, b.STOCK_SUBJECT, 100, 'FI', a.DELETE_TYPE, 
        CAST(a.REG_DATE as varchar(11)) as REG_DATE, CAST(a.UPTO_DATE as varchar(11)) as UPTO_DATE, a.SAFETY_STOCK, a.PURCHASE_LEADTIME
        from sp_item a
        LEFT JOIN SP_STOCK_SUBJECT b on a.STOCK_SUBJECT_CODE = b.STOCK_SUBJECT_CODE
        LEFT JOIN SP_CLASS c on a.class_code = c.class_code
        LEFT JOIN SP_ITEM_TYPE1 d on a.ITEM_TYPE1 = d.ITEM_TYPE
        LEFT JOIN SP_UNIT e on a.UOM_Q = e.UNIT_CODE
        LEFT JOIN SP_REPORTGROUP f on a.REPORTGROUP_CODE=f.reportgroup_code
        LEFT JOIN CURRENCY g on a.CURR_CODE = g.CURR_CODE
        order by a.item asc";

    $out = "KODE BARANG, NAMA BARANG, SPECIFICATION, MACHINE_CODE, ITEM_TYPE1, ITEM_TYPE, ITEM_TYPE_NAME, UOM_Q, UNIT, REPORTGROUP_CODE, REPORTGROUP_NAME, CLASS_CODE, CLASS, CURR_CODE, CURR_SHORT, STOCK_SUBJECT_CODE, STOCK_SUBJECT, KODE SECTION, SECTION NAME, DELETE_TYPE, REG_DATE, UPTO_DATE, SAFETY_STOCK, PURCHASE_LEADTIME";

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
    header("Content-Disposition: attachment; filename=item_spareparts_list.csv");
    echo $out;
?>