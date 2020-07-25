<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../../connect/conn.php");

$response = array();        $response2 = array();
$rowno=0;

if (isset($_SESSION['id_wms'])){
    $sql = "
    select distinct upper(cast(SYSDATETIME() as varchar(16))) as SYS_DATE, main.FLG, main.UPLOAD_DATE, main.ITEM_NO, i.DESCRIPTION as ITEM_NAME, 
    c.CLASS_1 + c.CLASS_2 as BATERY_TYPE, i.GRADE_CODE as CELL_GRADE, main.PO_NO, main.PO_LINE_NO, 
    case when main.STATUS = 'FM' then case when main.FLG = 'MPS' then  coalesce(main.WORK_ORDER, main.PO_NO + '-' + c.CLASS_1 + c.CLASS_2 + i.GRADE_CODE + '-' + main.PO_LINE_NO) 
    else  main.PO_NO + '-' + c.CLASS_1 + c.CLASS_2 + i.GRADE_CODE + '-' + main.PO_LINE_NO end else null end as WORK_ORDER, 
    case when main.FLG = 'MPS' then coalesce(main.CONSIGNEE, main.CONSIGNEE_SO) else main.CONSIGNEE end as CONSIGNEE, p.PACKING_TYPE_COMMENT as PACKAGE_TYPE, 
    case when main.FLG = 'MPS' then coalesce(main.DATE_CODE, main.DATE_CODE_SO) else main.DATE_CODE end as DATE_CODE, 
    CAST(main.CR_DATE as varchar(10)) as CR_DATE, CAST(main.REQUESTED_ETD as varchar(10)) as REQUESTED_ETD, main.STATUS, s.LOWER_ITEM_NO as LABEL_ITEM_NUMBER, s.DESCRIPTION as LABEL_NAME, 
    main.QTY, i.MAN_POWER, i.OPERATION_TIME, l.LABEL_TYPE_NAME as LABEL_TYPE, i.CAPACITY, main.REMARK 
    from (
    -----------------------------------------------------------------------------------------------------------------------------
    select 'MPS' as FLG, CAST(m_h.UPLOAD_DATE as varchar(10)) as UPLOAD_DATE, m_h.ITEM_NO, m_h.PO_NO,
    --decode(trim(translate(m_h.PO_LINE_NO, '0123456789', '         ')), null, to_char(to_number(m_h.PO_LINE_NO)), m_h.PO_LINE_NO) as PO_LINE_NO,
    m_h.PO_LINE_NO,
    m_h.WORK_ORDER, m_h.CONSIGNEE, m_h.DATE_CODE, m_h.CR_DATE, m_h.REQUESTED_ETD, m_h.STATUS, m_h.QTY, m_h.REMARK, 
    s.CONSIGNEE_FROM_JP as CONSIGNEE_SO,s.DATE_CODE as DATE_CODE_SO
    from MPS_HEADER m_h
    inner join (select distinct PO_NO, PO_LINE_NO from MPS_DETAILS where MPS_DATE >= (select CAST('2020-06-01' as date)) ) m_d
    on m_d.PO_NO = m_h.PO_NO
    left join (select distinct s_h.CUSTOMER_PO_NO as PO_NO, s_d.CUSTOMER_PO_LINE_NO as PO_LINE_NO, s_h.CONSIGNEE_FROM_JP, 
               s_d.DATE_CODE, s_d.ITEM_NO, s_d.ETD as REQUESTED_ETD
               from SO_HEADER s_h 
               inner join SO_DETAILS s_d on s_h.SO_NO = s_d.SO_NO 
               WHERE s_d.ETD >= (select CAST('2020-06-01' as date)) 
               and  s_d.IN_MPS  = '1' 
    ) s on m_d.PO_NO = s.PO_NO AND m_d.PO_LINE_NO = s.PO_LINE_NO
    UNION ALL ----------------------------------------------------------------------------------------------------------------
    select 'SO' as FLG, null as UPLOAD_DATE, s.ITEM_NO, s.PO_NO, s.PO_LINE_NO,
    '' as WORK_ORDER, s.CONSIGNEE_FROM_JP as CONSIGNEE, s.DATE_CODE as DATE_CODE, null as CR_DATE, s.REQUESTED_ETD, 'FM' as STATUS, s.QTY, '' as REMARK, 
    '' as CONSIGNEE_SO, '' as DATE_CODE_SO 
    from 
        (select s_d.ITEM_NO, s_h.CUSTOMER_PO_NO as PO_NO, s_d.CUSTOMER_PO_LINE_NO as PO_LINE_NO,
        --decode(trim(translate(s_d.CUSTOMER_PO_LINE_NO, '0123456789', '         ')), 
        --null, to_char(to_number(s_d.CUSTOMER_PO_LINE_NO)), s_d.CUSTOMER_PO_LINE_NO) as PO_LINE_NO, 
        s_h.CONSIGNEE_FROM_JP, s_d.DATE_CODE as DATE_CODE, 
        s_d.ETD as REQUESTED_ETD, s_d.QTY
        from SO_HEADER s_h, SO_DETAILS s_d 
        where s_h.SO_NO = s_d.SO_NO 
        and  s_d.BAL_QTY <> 0  
        and  s_d.ETD >= (select CAST('2020-06-01' as date))
        and  s_d.IN_MPS  = '1'
      ) s
      inner join 
      (select m_h.PO_NO, m_h.PO_LINE_NO 
       --decode(trim(translate(m_h.PO_LINE_NO, '0123456789', '         ')), null, to_char(to_number(m_h.PO_LINE_NO)), m_h.PO_LINE_NO) as PO_LINE_NO 
      from MPS_HEADER m_h, 
      (select distinct PO_NO, PO_LINE_NO from   MPS_DETAILS where  MPS_DATE >= (select CAST('2020-06-01' as date))) m_d 
      where  m_h.PO_NO = m_d.PO_NO 
      and  m_h.PO_LINE_NO = m_d.PO_LINE_NO 
      ) m on s.PO_NO + s.PO_LINE_NO = m.PO_NO+m.PO_LINE_NO 
      --and m.PO_NO+m.PO_LINE_NO is null
    ----------------------------------------------------------------------------------------------------------------
    ) main
    inner join ITEM i on main.item_no = i.item_no 
    left join CLASS c on i.class_code = c.class_code 
    left join (select s1.UPPER_ITEM_NO, s1.LOWER_ITEM_NO, s2.DESCRIPTION 
    from (select s.UPPER_ITEM_NO, s.LOWER_ITEM_NO 
          from (select UPPER_ITEM_NO, max(OPERATION_DATE) as OPERATION_DATE_MAX 
                 from STRUCTURE
                 where LOWER_ITEM_NO like '12_____' 
                 group by UPPER_ITEM_NO 
                ) s_max,
          STRUCTURE s 
          where s_max.UPPER_ITEM_NO = s.UPPER_ITEM_NO 
          and  s_max.OPERATION_DATE_MAX = s.OPERATION_DATE 
          and  s.LOWER_ITEM_NO like '12_____' 
          ) s1
          inner join ITEM s2 on s1.LOWER_ITEM_NO = s2.ITEM_NO
    ) s on main.item_no = s.upper_item_no
    left join LABEL_TYPE l on i.label_type = l.label_type_code 
    left join PACKING_TYPE p on i.PACKAGE_TYPE = p.PACKING_TYPE_JPN
    where SUBSTRING(UPPER(main.PO_NO),1,2) <>'SR'
    order by l.LABEL_TYPE_NAME, c.CLASS_1 + c.CLASS_2 ,i.GRADE_CODE,
	CAST(main.CR_DATE as varchar(10)),CAST(main.REQUESTED_ETD as varchar(10)),
	s.DESCRIPTION, main.PO_NO, main.PO_LINE_NO
    ";

    $data = sqlsrv_query($connect, strtoupper($sql));

    if($data === false ) {
        if( ($errors = sqlsrv_errors() ) != null) {  
            foreach( $errors as $error){  
                $msg .= $error[ 'message']."<br/>";  
            }  
        }
    }
    
    while($dt = sqlsrv_fetch_object($data)){
        array_push($response, $dt);

        $sqlD = "select PO_NO, PO_LINE_NO, convert(nvarchar(10),MPS_DATE, 105) as MPS_DATE_FORMAT, MPS_QTY
            from MPS_DETAILS 
            where PO_NO = '".$dt->PO_NO."'
            and PO_LINE_NO = '".$dt->PO_LINE_NO."'
            --and MPS_DATE >= getdate()-7
            and MPS_DATE >= '2020-06-01'
            order by MPS_DATE";
        $dataD = sqlsrv_query($connect, strtoupper($sqlD));
        while($dtD = sqlsrv_fetch_object($dataD)){
            array_push($response2, $dtD);
        }
    }

    $fp = fopen('mps_download_result.json', 'w');
	fwrite($fp, json_encode($response));
    fclose($fp);
    
    $fp2 = fopen('mps_download_result_details.json', 'w');
	fwrite($fp2, json_encode($response2));
	fclose($fp2);
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
    echo json_encode($msg);
}else{
	echo json_encode('success');
}
?>