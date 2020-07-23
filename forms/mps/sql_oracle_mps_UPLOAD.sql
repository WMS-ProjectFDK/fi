delete from MPS_HEADER

delete from MPS_DETAILS

-----------------------INSERT MPS HEADER -----------------------------------
insert into MPS_HEADER 
( 
ITEM_NO, 
ITEM_NAME, 
BATERY_TYPE, 
CELL_GRADE, 
PO_NO, 
PO_LINE_NO, 
WORK_ORDER, 
CONSIGNEE, 
PACKAGING_TYPE, 
DATE_CODE, 
CR_DATE, 
REQUESTED_ETD, 
STATUS, 
LABEL_ITEM_NUMBER, 
LABEL_NAME, 
QTY, 
MAN_POWER, 
OPERATEION_TIME, 
LABEL_TYPE, 
CAPACITY, 
UPLOAD_DATE, 
REMARK   '
) 
values 
( 
SET_SQL_NULL(xlsSheet.Cells(row, 1).Value) & ,   'ITEM_NO
substrb( & SET_SQL_NULL(xlsSheet.Cells(row, 2).Value) & ,1,30) ,   'ITEM_NAME
SET_SQL_NULL(xlsSheet.Cells(row, 3).Value) & ,   'BATERY_TYPE
SET_SQL_NULL(xlsSheet.Cells(row, 4).Value) & ,   'CELL_GRADE
SET_SQL_NULL(Trim(xlsSheet.Cells(row, 5).Value)) & ,  'PO_NO  '
SET_SQL_NULL(xlsSheet.Cells(row, 6).Value) & ,   'PO_LINE_NO
SET_SQL_NULL(xlsSheet.Cells(row, 7).Value) & ,   'WORK_ORDER
SET_SQL_NULL(xlsSheet.Cells(row, 8).Value) & ,   'CONSIGNEE
SET_SQL_NULL(xlsSheet.Cells(row, 9).Value) & ,   'PACKAGING_TYPE
SET_SQL_NULL(xlsSheet.Cells(row, 10).Value) & ,  'DATE_CODE
to_date( & SET_SQL(xlsSheet.Cells(row, 11).Value) & , 'YYYY/MM/DD'),  'CR_DATE
to_date( & SET_SQL(xlsSheet.Cells(row, 12).Value) & , 'YYYY/MM/DD'),  'REQUESTED_ETD
SET_SQL_NULL(xlsSheet.Cells(row, 13).Value) & ,  'STATUS
SET_SQL_NULL(xlsSheet.Cells(row, 14).Value) & ,  'LABEL_ITEM_NUMBER
SET_SQL_NULL(xlsSheet.Cells(row, 15).Value) & ,  'LABEL_NAME
SET_SQL_NULL(xlsSheet.Cells(row, 16).Value) & ,  'QTY
SET_SQL_NULL(xlsSheet.Cells(row, 17).Value) & ,  'MAN_POWER
SET_SQL_NULL(xlsSheet.Cells(row, 18).Value) & ,  'OPERATEION_TIME
SET_SQL_NULL(xlsSheet.Cells(row, 19).Value) & ,  'LABEL_TYPE
SET_SQL_NULL(xlsSheet.Cells(row, 20).Value) & ,  'CAPACITY
to_date(' & wkSysdate & ', 'YYYY/MM/DD HH24:MI:SS'),   'UPLOAD_DATE
substrb( &  SET_SQL_NULL(xlsSheet.Cells(row, 21).Value)  & ,1,50)  'REMARK '
) 
'

-------------------------------- INSERT MPS DETAILS

insert into MPS_DETAILS (PO_NO, PO_LINE_NO, MPS_DATE, MPS_QTY, UPLOAD_DATE) 
VALUES ( 
	SET_SQL_NULL(Trim(xlsSheet.Cells(row, 5).Value)) & ,     'PO_NO '//(mod Ver1.8)
	SET_SQL_NULL(xlsSheet.Cells(row, 6).Value) & ,           'PO_LINE_NO
 	to_date( & SET_SQL(xlsSheet.Cells(titleLine, cell).Value) & , 'YYYY/MM/DD'),  'MPS_DATE
	SET_SQL_NULL(xlsSheet.Cells(row, cell).Value) & ,        'MPS_QTY
 	to_date(' & wkSysdate & ', 'YYYY/MM/DD HH24:MI:SS')   'UPLOAD_DATE
) 


--------------- MPS_HEADER_RIREKI -------------------------------- 

insert into MPS_HEADER_RIREKI 
select * 
from   MPS_HEADER 
where  UPLOAD_DATE = to_date(' & wkSysdate & ', 'YYYY/MM/DD HH24:MI:SS')


-------------- MPS_DETAILS_RIREKI --------------------------------

insert into MPS_DETAILS_RIREKI 
select * 
from   MPS_DETAILS 
where  UPLOAD_DATE = to_date(' & wkSysdate & ', 'YYYY/MM/DD HH24:MI:SS')


-------------- MPS_REMAIN_HISTORY --------------------------------

delete from MPS_REMAIN_HISTORY p 
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
) 

-------------- MPS_REMAIN_HISTORY --------------------------------

insert into MPS_REMAIN_HISTORY 
   (HISTORY_CREATE_DATE, 
    WORK_ORDER, PO_NO, PO_LINE_NO, ITEM_NO, QTY,  
	 KURAIRE_QTY, BAL_QTY, REG_DATE, UPTO_DATE, LAST_KURAIRE_DATE) 
select 
    to_date(' & wkSysdate & ', 'YYYY/MM/DD HH24:MI:SS') as HISTORY_CREATE_DATE, 
    WORK_ORDER, PO_NO, PO_LINE_NO, ITEM_NO, QTY, 
    KURAIRE_QTY, BAL_QTY, REG_DATE, UPTO_DATE, LAST_KURAIRE_DATE 
from  MPS_REMAIN 


-------------- MPS_REMAIN --------------------------------

insert into MPS_REMAIN 
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
where  h.WORK_ORDER is not null 


-------------- MPS_REMAIN --------------------------------

update MPS_REMAIN u 
set    ( u.KURAIRE_QTY, u.BAL_QTY, u.LAST_KURAIRE_DATE ) 
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
           ) 

-------------- PRODUCT_PLAN_HISTORY --------------------------------

delete from PRODUCT_PLAN_HISTORY p 
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
   ) 


-------------- PRODUCT_PLAN_HISTORY --------------------------------

insert into PRODUCT_PLAN_HISTORY 
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
from PRODUCT_PLAN 


-------------- PRODUCT_PLAN --------------------------------

insert into PRODUCT_PLAN 
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
  and  d.MPS_QTY      > 0 



----------------------------------------------------------------------------------------------------------------

select upper(to_char(max(UPLOAD_DATE),'dd/mon/yyyy hh24:mi')) as UPLOAD_DATE
from   MPS_HEADER


----------------------------------------------------------------------------------------------------------------
select PERSON
from   PERSON
where  PERSON_CODE = 'FI0122'