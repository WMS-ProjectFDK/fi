select trunc(add_months(trunc(sysdate), -1),'mm') as MPS_DATE_FROM, 
       last_day(add_months(trunc(sysdate), 3)) as MPS_DATE_TO, 
       last_day(add_months(trunc(sysdate), 3)) - trunc(add_months(trunc(sysdate), -1),'mm') + 1 as MPS_DATE_LIST_CNT 
from   dual 
Set rec_h = objDb.Execute(SQL)
MPS_DATE_FROM     = rec_h(MPS_DATE_FROM)
MPS_DATE_TO       = rec_h(MPS_DATE_TO)
MPS_DATE_LIST_CNT = rec_h(MPS_DATE_LIST_CNT)
ReDim MPS_DATE_LIST(MPS_DATE_LIST_CNT, 1)


---------------------------------------------------------------------------------------------------------------------------------------------------

select  upper(to_char(sysdate,'dd/mon/yyyy hh24:mi')) as SYS_DATE, 
        main.FLG, 
        main.UPLOAD_DATE, 
        main.ITEM_NO, 
        i.DESCRIPTION as ITEM_NAME, 
        c.CLASS_1 || c.CLASS_2 as BATERY_TYPE, 
        i.GRADE_CODE as CELL_GRADE, 
        main.PO_NO, 
        main.PO_LINE_NO, 
        case when main.STATUS = 'FM'  
          then  
            case when main.FLG = 'MPS'  
              then  nvl(main.WORK_ORDER, main.PO_NO || '-' || c.CLASS_1 || c.CLASS_2 || i.GRADE_CODE || '-' || main.PO_LINE_NO)  
              else  main.PO_NO || '-' || c.CLASS_1 || c.CLASS_2 || i.GRADE_CODE || '-' || main.PO_LINE_NO  
            end 
          else  
            null  
        end as WORK_ORDER,  
        case when main.FLG = 'MPS' 
          then nvl(main.CONSIGNEE, main.CONSIGNEE_SO) 
          else main.CONSIGNEE 
        end as CONSIGNEE, 
        p.PACKING_TYPE_COMMENT as PACKAGE_TYPE, 
        case when main.FLG = 'MPS' 
          then nvl(main.DATE_CODE, main.DATE_CODE_SO) 
          else main.DATE_CODE 
        end as DATE_CODE, 
        main.CR_DATE, 
        main.REQUESTED_ETD, 
        main.STATUS, 
        s.LOWER_ITEM_NO as LABEL_ITEM_NUMBER, 
        s.DESCRIPTION as LABEL_NAME, 
        main.QTY, 
        i.MAN_POWER, 
        i.OPERATION_TIME, 
        l.LABEL_TYPE_NAME as LABEL_TYPE, 
        i.CAPACITY, 
        main.REMARK 
from    ( 
         select 'MPS' as FLG, 
                 to_char(m_h.UPLOAD_DATE,'yyyymmdd hh24miss') as UPLOAD_DATE,
                 m_h.ITEM_NO, 
                 m_h.PO_NO, 
                 decode(trim(translate(m_h.PO_LINE_NO, '0123456789', '         ')), null, to_char(to_number(m_h.PO_LINE_NO)), m_h.PO_LINE_NO) as PO_LINE_NO, 
                 m_h.WORK_ORDER, 
                 m_h.CONSIGNEE, 
                 m_h.DATE_CODE, 
                 m_h.CR_DATE, 
                 m_h.REQUESTED_ETD, 
                 m_h.STATUS, 
                 m_h.QTY, 
                 m_h.REMARK, 
                 s.CONSIGNEE_FROM_JP as CONSIGNEE_SO, 
                 case when s.DATE_CODE is null 
                   then 
                     GET_DATE_CODE(s.REQUESTED_ETD, s.ITEM_NO) 
                   else 
                     s.DATE_CODE 
                 end as DATE_CODE_SO 
          from   MPS_HEADER m_h, 
                 ( select distinct PO_NO, PO_LINE_NO 
                   from   MPS_DETAILS 
                   where  trunc(MPS_DATE) >= trunc(add_months(trunc(sysdate), -1),'mm') 
                 ) m_d 
                ,( select distinct s_h.CUSTOMER_PO_NO as PO_NO, s_d.CUSTOMER_PO_LINE_NO as PO_LINE_NO, 
                          s_h.CONSIGNEE_FROM_JP, s_d.DATE_CODE, s_d.ITEM_NO, s_d.ETD as REQUESTED_ETD 
                   from   SO_HEADER s_h, 
                          SO_DETAILS s_d 
                   where  s_h.SO_NO   =  s_d.SO_NO 
                     and  trunc(s_d.ETD) >= trunc(add_months(trunc(sysdate), -1),'mm') 
                     and  s_d.IN_MPS  = '1' 
                 ) s 
          where  m_h.PO_NO      = m_d.PO_NO 
            and  m_h.PO_LINE_NO = m_d.PO_LINE_NO 
            and  m_d.PO_NO      = s.PO_NO (+) 
            and  m_d.PO_LINE_NO = s.PO_LINE_NO (+) 
          union all 
          select 'SO' as FLG, 
                 null as UPLOAD_DATE, 
                 s.ITEM_NO, 
                 s.PO_NO, 
                 s.PO_LINE_NO, 
                 '' as WORK_ORDER, 
                 s.CONSIGNEE_FROM_JP as CONSIGNEE, 
                 case when s.DATE_CODE is null 
                   then 
                     GET_DATE_CODE(s.REQUESTED_ETD, s.ITEM_NO) 
                   else 
                     s.DATE_CODE 
                 end as DATE_CODE, 
                 null as CR_DATE, 
                 s.REQUESTED_ETD, 
                 'FM' as STATUS, 
                 s.QTY, 
                 '' as REMARK, 
                 '' as CONSIGNEE_SO, 
                 '' as DATE_CODE_SO 
          from   ( select s_d.ITEM_NO, 
                          s_h.CUSTOMER_PO_NO as PO_NO, 
                          decode(trim(translate(s_d.CUSTOMER_PO_LINE_NO, '0123456789', '         ')), null, to_char(to_number(s_d.CUSTOMER_PO_LINE_NO)), s_d.CUSTOMER_PO_LINE_NO) as PO_LINE_NO, 
                          s_h.CONSIGNEE_FROM_JP, 
                          s_d.DATE_CODE as DATE_CODE, 
                          s_d.ETD as REQUESTED_ETD, 
                          s_d.QTY 
                   from   SO_HEADER s_h, 
                          SO_DETAILS s_d 
                   where  s_h.SO_NO   =  s_d.SO_NO 
                     and  s_d.BAL_QTY <> 0  
                     and  trunc(s_d.ETD) >= trunc(add_months(trunc(sysdate), -1),'mm') 
                     and  s_d.IN_MPS  = '1' 
                 ) s, 
                 ( select m_h.PO_NO, 
                          decode(trim(translate(m_h.PO_LINE_NO, '0123456789', '         ')), null, to_char(to_number(m_h.PO_LINE_NO)),  m_h.PO_LINE_NO) as PO_LINE_NO 
                   from   MPS_HEADER m_h, 
                          ( select distinct PO_NO, PO_LINE_NO 
                            from   MPS_DETAILS 
                            where  trunc(MPS_DATE) >= trunc(add_months(trunc(sysdate), -1),'mm') 
                          ) m_d 
                   where  m_h.PO_NO      = m_d.PO_NO 
                     and  m_h.PO_LINE_NO = m_d.PO_LINE_NO 
                 ) m 
          where  s.PO_NO || s.PO_LINE_NO = m.PO_NO(+)  || m.PO_LINE_NO(+) 
            and  m.PO_NO || m.PO_LINE_NO is null 
        ) main, 
        ITEM i, 
        CLASS c, 
        ( select s1.UPPER_ITEM_NO, s1.LOWER_ITEM_NO, 
                 s2.DESCRIPTION 
          from   ( select s.UPPER_ITEM_NO, s.LOWER_ITEM_NO 
                   from   ( select   UPPER_ITEM_NO, max(OPERATION_DATE) as OPERATION_DATE_MAX 
                            from     STRUCTURE 
                            where    LOWER_ITEM_NO like '12_____' 
                            group by UPPER_ITEM_NO 
                          ) s_max, 
                          STRUCTURE s 
                   where  s_max.UPPER_ITEM_NO      = s.UPPER_ITEM_NO 
                     and  s_max.OPERATION_DATE_MAX = s.OPERATION_DATE 
                     and  s.LOWER_ITEM_NO like '12_____' 
                 ) s1, 
                 ITEM s2 
          where  s1.LOWER_ITEM_NO = s2.ITEM_NO (+) 
        ) s, 
        LABEL_TYPE l, 
        PACKING_TYPE p 
where   main.ITEM_NO   = i.ITEM_NO (+) 
  and   i.CLASS_CODE   = c.CLASS_CODE (+) 
  and   main.ITEM_NO   = s.UPPER_ITEM_NO (+) 
  and   i.LABEL_TYPE   = l.LABEL_TYPE_CODE (+) 
  and   i.PACKAGE_TYPE = p.PACKING_TYPE_JPN (+) 
  and   substrb(UPPER(main.PO_NO),1,2) <>'SR'  // (add Ver2.1)
order by l.LABEL_TYPE_NAME, c.CLASS_1 || c.CLASS_2 ,i.GRADE_CODE,main.CR_DATE,main.REQUESTED_ETD,s.DESCRIPTION, main.PO_NO, main.PO_LINE_NO 


-------------------------------------------------------------------------------------------------------------------------------------------------------

select   MPS_DATE, MPS_QTY,
to_char(MPS_DATE, 'yyyymmdd') as MPS_DATE_FORMTAT
from     MPS_DETAILS 
where    PO_NO      = '$po_no'
and   PO_LINE_NO = '$po_line_no'
and   trunc(MPS_DATE) >= trunc(sysdate) - 7
and   trunc(MPS_DATE) >= trunc(add_months(trunc(sysdate), -1),'mm')
order by MPS_DATE "