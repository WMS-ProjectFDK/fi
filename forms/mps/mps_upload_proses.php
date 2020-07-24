<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");

session_start();
$user = $_SESSION['id_wms'];

include ("../../class/excel_reader.php");
include ("../../connect/conn.php");

$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount($sheet_index=0);

$success = 0;       $failed = 0;

for($i=5;$i<=$hasildata;$i++){
    // $item = trim($data->val($i,1));
    // $bln = intval($data->val($i,2));
    // $year = trim($data->val($i,3));
    // $qty = trim($data->val($i,4));
    // $sts_bundle = trim($data->val($i,5));
    // $qty_b = trim($data->val($i,6));
    
    // delete MPS_HEADER
    $del1 = "delete from MPS_HEADER";
    $data_del1 = sqlsrv_query($connect, $del1);

    // delete from MPS_DETAILS
    $del2 = "delete from MPS_DETAIL";
    $data_del2 = sqlsrv_query($connect, $del2);

    // INSERT MPS HEADER
    $field1 = "ITEM_NO,";                    $value1 = "";
    $field1 .= "ITEM_NAME,";                 $value1 .= "";
    $field1 .= "BATERY_TYPE,";               $value1 .= "";
    $field1 .= "CELL_GRADE,";                $value1 .= "";
    $field1 .= "PO_NO,";                     $value1 .= "";
    $field1 .= "PO_LINE_NO,";                $value1 .= "";
    $field1 .= "WORK_ORDER,";                $value1 .= "";
    $field1 .= "CONSIGNEE,";                 $value1 .= "";
    $field1 .= "PACKAGING_TYPE,";            $value1 .= "";
    $field1 .= "DATE_CODE,";                 $value1 .= "";
    $field1 .= "CR_DATE,";                   $value1 .= "";
    $field1 .= "REQUESTED_ETD,";             $value1 .= "";
    $field1 .= "STATUS,";                    $value1 .= "";
    $field1 .= "LABEL_ITEM_NUMBER,";         $value1 .= "";
    $field1 .= "LABEL_NAME,";                $value1 .= "";
    $field1 .= "QTY,";                       $value1 .= "";
    $field1 .= "MAN_POWER,";                 $value1 .= "";
    $field1 .= "OPERATEION_TIME,";           $value1 .= "";
    $field1 .= "LABEL_TYPE,";                $value1 .= "";
    $field1 .= "CAPACITY,";                  $value1 .= "";
    $field1 .= "UPLOAD_DATE,";               $value1 .= "";
    $field1 .= "REMARK";                     $value1 .= "";

    $ins1 = "insert into MPS_HEADER (".$field1.") VALUES (".$value1.")";
    $data_ins1 = sqlsrv_query($connect, $ins1);

    // INSERT MPS DETAILS
    $field2 = "PO_NO,";                     $value2 = "";
    $field2 .= "PO_LINE_NO,";               $value2 .= "";
    $field2 .= "MPS_DATE,";                 $value2 .= "";
    $field2 .= "MPS_QTY,";                  $value2 .= "";
    $field2 .= "UPLOAD_DATE";               $value2 .= "";

    $ins2 = "insert into MPS_DETAILS (".$field2.") VALUES (".$value2.")";
    $data_ins2 = sqlsrv_query($connect, $ins2);

    // INSERT MPS_HEADER_RIREKI
    $ins3 = "insert into MPS_HEADER_RIREKI 
        select * 
        from   MPS_HEADER 
        where  UPLOAD_DATE = to_date(' & wkSysdate & ', 'YYYY/MM/DD HH24:MI:SS')";
    $data_ins3 = sqlsrv_query($connect, $ins3);

    // INSERT MPS_DETAILS_RIREKI
    $ins4 = "insert into MPS_DETAILS_RIREKI 
        select * 
        from   MPS_DETAILS 
        where  UPLOAD_DATE = to_date(' & wkSysdate & ', 'YYYY/MM/DD HH24:MI:SS')";
    $data_ins4 = sqlsrv_query($connect, $ins4);

    // DELETE MPS_REMAIN_HISTORY
    $del3 = "delete from MPS_REMAIN_HISTORY p 
        where exists  
        ( 
        select 1 
        from   ( select row_number() over ( order by HISTORY_CREATE_DATE desc ) as HISTORY_SEQ, 
                        HISTORY_CREATE_DATE 
                from   ( select distinct HISTORY_CREATE_DATE 
                        from   MPS_REMAIN_HISTORY 
                        ) 
            ) h 
        where  h.HISTORY_SEQ         >= 3 
        and  h.HISTORY_CREATE_DATE = p.HISTORY_CREATE_DATE 
        )";
    $data_del3 = sqlsrv_query($connect, $del3);
    
    // INSERT MPS_REMAIN_HISTORY
    $ins4 = "insert into MPS_REMAIN_HISTORY 
        (HISTORY_CREATE_DATE, 
        WORK_ORDER, PO_NO, PO_LINE_NO, ITEM_NO, QTY,  
        KURAIRE_QTY, BAL_QTY, REG_DATE, UPTO_DATE, LAST_KURAIRE_DATE) 
    select 
        to_date(' & wkSysdate & ', 'YYYY/MM/DD HH24:MI:SS') as HISTORY_CREATE_DATE, 
        WORK_ORDER, PO_NO, PO_LINE_NO, ITEM_NO, QTY, 
        KURAIRE_QTY, BAL_QTY, REG_DATE, UPTO_DATE, LAST_KURAIRE_DATE 
    from  MPS_REMAIN";
    $data_ins4 = sqlsrv_query($connect, $ins4);

    // INSERT MPS_REMAIN
    $ins5 = "insert into MPS_REMAIN 
        select h.WORK_ORDER, 
            h.PO_NO, 
            h.PO_LINE_NO, 
            h.ITEM_NO, 
            h.QTY, 
            0 as KURAIRE_QTY, 
            h.QTY as BAL_QTY, 
            to_date(' & wkSysdate & ', 'YYYY/MM/DD HH24:MI:SS') as REG_DATE, 
            to_date(' & wkSysdate & ', 'YYYY/MM/DD HH24:MI:SS') as UPTO_DATE, 
            null as LAST_KURAIRE_DATE 
        from   MPS_HEADER h 
        where  h.WORK_ORDER is not null";
    $data_ins5 = sqlsrv_query($connect, $ins5);
            
    // UPDATE MPS_REMAIN
    $upd1 = "update MPS_REMAIN u 
        set ( u.KURAIRE_QTY, u.BAL_QTY, u.LAST_KURAIRE_DATE ) 
             = ( select  k.KURAIRE_TOTAL_QTY, u.QTY - k.KURAIRE_TOTAL_QTY as BAL_QTY, k.LAST_KURAIRE_DATE 
                 from    ( select   t.WO_NO, t.ITEM_NO, 
                                    sum(t.SLIP_QUANTITY) as KURAIRE_TOTAL_QTY,
                                    max(t.OPERATION_DATE) as LAST_KURAIRE_DATE 
                           from     TRANSACTION t, 
                                   MPS_REMAIN r 
                           where    t.SLIP_TYPE = '80'
                             and    r.WORK_ORDER = t.WO_NO 
                             and    r.ITEM_NO    = t.ITEM_NO 
                           group by t.WO_NO, t.ITEM_NO 
                         ) k 
                 where   k.WO_NO = u.WORK_ORDER 
               ) 
        where exists 
               ( select   1 
                 from     TRANSACTION t 
                 where    t.SLIP_TYPE = '80'
                   and    u.WORK_ORDER = t.WO_NO 
                   and    u.ITEM_NO    = t.ITEM_NO 
                 group by t.WO_NO, t.ITEM_NO 
               )";
    $data_upd1 = sqlsrv_query($connect, $upd1);

    // DELETE PRODUCT_PLAN_HISTORY
    $del4 = "delete from PRODUCT_PLAN_HISTORY p 
        where exists  
       ( 
            select 1 
            from   ( select row_number() over ( order by HISTORY_CREATE_DATE desc ) as HISTORY_SEQ, 
                        HISTORY_CREATE_DATE 
                 from   ( select distinct HISTORY_CREATE_DATE 
                          from   PRODUCT_PLAN_HISTORY 
                        ) 
               ) h 
            where  h.HISTORY_SEQ         >= 3 
            and  h.HISTORY_CREATE_DATE = p.HISTORY_CREATE_DATE 
       )";
    $data_del4 = sqlsrv_query($connect, $del4);

    // INSERT PRODUCT_PLAN_HISTORY
    $ins6 = "insert into PRODUCT_PLAN_HISTORY 
        (HISTORY_CREATE_DATE, 
        OPERATION_DATE, SECTION_CODE, PRODUCT_LOT_NUMBER, 
        ITEM_NO, KURAIRE_DATE, BM_ITEM_NO, LEVEL_NO, STATION_CODE, 
        PROGRESS_STATUS, BUYER_CODE, SHIPTO_CODE, CARVED_STAMP, 
        PLAN_CREATE_DATE, PLAN_OPERATE_DATE, PRODUCT_QUANTITY, 
        MATERIAL_REQ_DATE, ISSUE_REQ_DATE, PRODUCT_REQ_DATE, RELEASE_DATE, 
        PRODUCT_FIN_DATE, COMPLETE_QUANTITY, CANCEL_QUANTITY, 
        REMAINDER_QUANTITY, WO_NO, PRODUCT_LINE, DATE_CODE, GRADE) 
    select 
        to_date(' & wkSysdate & ', 'YYYY/MM/DD HH24:MI:SS') as HISTORY_CREATE_DATE, 
        OPERATION_DATE, SECTION_CODE, PRODUCT_LOT_NUMBER, 
        ITEM_NO, KURAIRE_DATE, BM_ITEM_NO, LEVEL_NO, STATION_CODE, 
        PROGRESS_STATUS, BUYER_CODE, SHIPTO_CODE, CARVED_STAMP, 
        PLAN_CREATE_DATE, PLAN_OPERATE_DATE, PRODUCT_QUANTITY, 
        MATERIAL_REQ_DATE, ISSUE_REQ_DATE, PRODUCT_REQ_DATE, RELEASE_DATE, 
        PRODUCT_FIN_DATE, COMPLETE_QUANTITY, CANCEL_QUANTITY, 
        REMAINDER_QUANTITY, WO_NO, PRODUCT_LINE, DATE_CODE, GRADE 
    from PRODUCT_PLAN"
    $data_ins6 = sqlsrv_query($connect, $ins6);

    // INSERT PRODUCT_PLAN
    $ins7 = "insert into PRODUCT_PLAN 
        ( 
        OPERATION_DATE, 
        SECTION_CODE, 
        PRODUCT_LOT_NUMBER, 
        ITEM_NO, 
        KURAIRE_DATE, 
        BM_ITEM_NO, 
        LEVEL_NO, 
        STATION_CODE, 
        PROGRESS_STATUS, 
        BUYER_CODE, 
        SHIPTO_CODE, 
        CARVED_STAMP, 
        PLAN_CREATE_DATE, 
        PLAN_OPERATE_DATE, 
        PRODUCT_QUANTITY, 
        MATERIAL_REQ_DATE, 
        ISSUE_REQ_DATE, 
        PRODUCT_REQ_DATE, 
        RELEASE_DATE, 
        PRODUCT_FIN_DATE, 
        COMPLETE_QUANTITY, 
        CANCEL_QUANTITY, 
        REMAINDER_QUANTITY, 
        WO_NO, 
        PRODUCT_LINE, 
        DATE_CODE, 
        GRADE, 
        PO_NO,       '(add Ver2.0)
        PO_LINE_NO   '(add Ver2.0)
        ) 
    select to_date(' & wkSysdate & ', 'YYYY/MM/DD HH24:MI:SS') as OPERATION_DATE, 
        s.SECTION_CODE, 
        to_char(SEQ_PPLAN.nextval, 'fm0999999') as PRODUCT_LOT_NUMBER, 
        h.ITEM_NO, 
        d.MPS_DATE as KURAIRE_DATE, 
        h.ITEM_NO as BM_ITEM_NO, 
        0 as LEVEL_NO, 
        '100001' as STATION_CODE, 
        'A' as PROGRESS_STATUS, 
        null as BUYER_CODE, 
        null as SHIPTO_CODE, 
        null as CARVED_STAMP, 
        to_date(' & wkSysdate & ', 'YYYY/MM/DD HH24:MI:SS') as PLAN_CREATE_DATE, 
        to_date(' & wkSysdate & ', 'YYYY/MM/DD HH24:MI:SS') as PLAN_OPERATE_DATE, 
        d.MPS_QTY as PRODUCT_QUANTITY, 
        d.MPS_DATE - 2 as MATERIAL_REQ_DATE, 
        d.MPS_DATE - 1 as ISSUE_REQ_DATE, 
        d.MPS_DATE     as PRODUCT_REQ_DATE, 
        null as RELEASE_DATE, 
        null as PRODUCT_FIN_DATE, 
        0 as COMPLETE_QUANTITY, 
        0 as CANCEL_QUANTITY, 
        d.MPS_QTY as REMAINDER_QUANTITY, 
        h.WORK_ORDER as WO_NO, 
        null as PRODUCT_LINE, 
        h.DATE_CODE as DATE_CODE, 
        null as GRADE, 
        h.PO_NO,       '(add Ver2.0)
        h.PO_LINE_NO   '(add Ver2.0)
    from   MPS_HEADER h, 
        MPS_DETAILS d, 
        ITEM i, 
        SECTION s 
    where  h.PO_NO        = d.PO_NO 
    and  h.PO_LINE_NO   = d.PO_LINE_NO 
    and  h.ITEM_NO      = i.ITEM_NO 
    and  ( h.STATUS <> 'BU' and h.STATUS <> 'XBU' )
    and  i.SECTION_CODE = s.SECTION_CODE (+) 
    and  d.MPS_QTY      > 0";
    $data_ins7 = sqlsrv_query($connect, $ins7);
}
echo json_encode("Success = ".$success.", Failed = ".$failed);
?>