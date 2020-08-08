SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

create proc [dbo].[SALES_RESTORE] (
    @do_no nvarchar(30),
    @bl_date date
)as

 BEGIN TRAN TT

 insert into oa_invoice_trn(
                   EB_TYPE            ,
                   COMPANY_CODE       ,
                   SECTION_CODE       ,
                   PERSON_CODE        ,
                   VENDOR_CODE        ,
                   DIST_COUNTRY_CODE  ,
                   DATA_DATE          ,
                   INVOICE_NO         ,
                   PACKING_NO         ,
                   ITEM_NO            ,
                   ITEM_TYPE          ,
                   QUANTITY           ,
                   PURCHASE_PRICE     ,
                   CURR_CODE          ,
                   EXCHANGE_RATE      ,
                   PURCHASE_AMOUNT    ,
                   PO_NO              ,
                   LINE_NO            ,
                   OPERATION_DATE     ,
                   DATA_SEQ_NO        ,
                   DATA_SOURCE_TYPE   ,
                   WAREHOUSE_CODE     ,
                   YEN_RATE           ,
                   INPORT_FLG         ,
                   PACK_QTY
               ) select
                   'E',
                   DOH_REC.CUSTOMER_CODE   ,
                   null                    ,
                   null                    ,
                   CU2_REC.COMPANY_CODE    ,
                   105                     ,
                   @BL_DATE               ,
                   substring(DOH_REC.DO_NO,1,23),
                   null          ,
                   DOS_REC.ITEM_NO         ,
                   null                    ,
                   DOS_REC.SO_QTY  *-1        ,
                   DOS_REC.U_PRICE         ,
                   DOH_REC.CURR_CODE       ,
                   null                    ,
                   null                    ,
                   DOS_REC.CUSTOMER_PO_NO  ,
                   SOD_REC.CUSTOMER_PO_LINE_NO ,
                   getdate()                 ,
                   null                    ,
                   'OA'                    ,
                   0                       ,
                   0                       ,
                   0                       ,
                   ITM_REC.EXTERNAL_UNIT_NUMBER
                from do_header DOH_REC
                inner join company CU2_REC 
                on cu2_rec.company_code = doh_rec.customer_code
                inner join do_so dos_rec
                on doh_rec.do_no = dos_rec.do_no
                inner join so_details SOD_REC
                on sod_rec.so_no = dos_rec.so_no and sod_rec.LINE_NO = dos_rec.so_line_no
                inner join item ITM_REC
                on itm_rec.item_no = sod_rec.item_no
                where DOH_REC.do_no = @do_no and COUNTRY_CODE=192

 insert into fdk_po_transit_trn(
                       COMPANY_CODE            ,
                       SECTION_CODE            ,
                       PERSON_CODE             ,
                       VENDOR_CODE             ,
                       DIST_COUNTRY_CODE       ,
                       DATA_DATE               ,
                       INVOICE_NO              ,
                       INVOICE_LINE_NO         ,
                       PACKING_NO              ,
                       PO_NO                   ,
                       PO_LINE_NO              ,
                       ITEM                    ,
                       ITEM_NO                 ,
                       QUANTITY                ,
                       PURCHASE_PRICE          ,
                       CURR_CODE               ,
                       EXCHANGE_RATE           ,
                       PURCHASE_AMOUNT         ,
                       OPERATION_DATE          ,
                       DATA_SEQ_NO             ,
                       DATA_SOURCE_TYPE        ,
                       WAREHOUSE_CODE          ,
                       YEN_RATE                ,
                       INPORT_FLG              ,
                       PACK_QTY                ,
                       SHIPTO_CODE
                   ) select 
                       DOH_REC.CUSTOMER_CODE   ,
                       null                    ,
                       null                    ,
                       CU2_REC.COMPANY_CODE    ,
                       105                     ,
                       @BL_DATE               ,
                       DOH_REC.DO_NO           ,
                       DOS_REC.LINE_NO         ,
                       null                    ,
                       DOS_REC.CUSTOMER_PO_NO  ,
                       SOD_REC.CUSTOMER_PO_LINE_NO  ,
                       SUBSTRING(ITM_REC.ITEM,1,30)           ,
                       DOS_REC.ITEM_NO         ,
                       DOS_REC.SO_QTY * -1         ,
                       DOS_REC.U_PRICE         ,
                       DOH_REC.CURR_CODE       ,
                       null                    ,
                       SOD_REC.AMT_O           ,
                       GETDATE()                 ,
                       null                    ,
                       'PT'                    ,
                       0                       ,
                       0                       ,
                       0                       ,
                       ITM_REC.EXTERNAL_UNIT_NUMBER,
                       null
                from do_header DOH_REC
                inner join company CU2_REC 
                on cu2_rec.company_code = doh_rec.customer_code
                inner join do_so dos_rec
                on doh_rec.do_no = dos_rec.do_no
                inner join so_details SOD_REC
                on sod_rec.so_no = dos_rec.so_no and sod_rec.LINE_NO = dos_rec.so_line_no
                inner join item ITM_REC
                on itm_rec.item_no = sod_rec.item_no
                where DOH_REC.do_no = @do_no and (select top 1 config_flag from CONFIGURATION where CONFIG_KEY = 'FI_PO_TRANSIT') = 1

INSERT INTO TRANSACTION_SALES (
                  OPERATION_DATE,
                  SECTION_CODE,
                  ITEM_NO,
                  ITEM_CODE,
                  ITEM_NAME,
                  ITEM_DESCRIPTION,
                  STOCK_SUBJECT_CODE,
                  ACCOUNTING_MONTH,
                  SLIP_DATE,
                  SLIP_TYPE,
                  SLIP_NO,
                  SLIP_QUANTITY,
                  SLIP_PRICE,
                  SLIP_AMOUNT,
                  CURR_CODE,
                  STANDARD_PRICE,
                  STANDARD_AMOUNT,
                  SUPPLIERS_PRICE,
                  COMPANY_CODE,
                  ORDER_NUMBER,
                  LINE_NO,
                  COST_PROCESS_CODE,
                  COST_SUBJECT_CODE,
                  PRODUCT_LOT_NUMBER,
                  PURCHASE_QUANTITY,
                  PURCHASE_PRICE,
                  PURCHASE_AMOUNT,
                  PURCHASE_UNIT,
                  UNIT_STOCK,
                  EX_RATE,
                  ANSWER_NO,
                  REMARK1,
                  REMARK2,
                  CUSTOMER_PO_NO,
                  WO_NO,
                  DATE_CODE,
                  REMARK3,
                  INV_NO
               ) select 
                  getdate()                   ,
                  ITM_REC.SECTION_CODE      ,
                  DOS_REC.ITEM_NO           ,
                  ITM_REC.ITEM_CODE         ,
                  ITM_REC.ITEM              ,
                  substring(ITM_REC.DESCRIPTION,0,30)       ,
                  ITM_REC.STOCK_SUBJECT_CODE,
                  CONVERT(nvarchar(6), @BL_DATE, 112),
                  @BL_DATE                 ,
                  '84'                      ,
                  DOH_REC.DO_NO             ,
                  DOS_REC.QTY   *-1            ,
                  DOS_REC.U_PRICE           ,
                  round(DOS_REC.QTY * DOS_REC.U_PRICE,2) *-1,
                  DOH_REC.CURR_CODE         ,
                  ITM_REC.STANDARD_PRICE    ,
                  round(ITM_REC.STANDARD_PRICE * DOS_REC.QTY,8) *-1,
                  ITM_REC.SUPPLIERS_PRICE   ,
                  DOH_REC.CUSTOMER_CODE     ,
                  NULL                      ,
                  NULL                      ,
                  ITM_REC.COST_PROCESS_CODE ,
                  ITM_REC.COST_SUBJECT_CODE ,
                  NULL                      ,
                  NULL                      ,
                  NULL                      ,
                  NULL                      ,
                  NULL                      ,
                  ITM_REC.UNIT_STOCK        ,
                  DOH_REC.EX_RATE           ,
                  NULL                      ,
                  NULL                      ,
                  NULL                      ,
                  NULL                      ,
                  NULL                      ,
                  NULL                      ,
                  NULL                      ,
                  DOH_REC.INV_NO              
                from do_header DOH_REC
                    inner join company CU2_REC 
                    on cu2_rec.company_code = doh_rec.customer_code
                    inner join do_so dos_rec
                    on doh_rec.do_no = dos_rec.do_no
                    inner join so_details SOD_REC
                    on sod_rec.so_no = dos_rec.so_no and sod_rec.LINE_NO = dos_rec.so_line_no
                    inner join item ITM_REC
                    on itm_rec.item_no = sod_rec.item_no
                    where DOH_REC.do_no = @do_no

UPDATE DO_HEADER SET BL_DATE=null,SHIP_END_FLG=NULL WHERE DO_NO = @do_no ;

COMMIT TRAN TT

GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
 
 

 CREATE proc [dbo].[SALES_UPDATE] (
     @do_no varchar(20),
     @bl_Date date
 )as

BEGIN TRAN TT

INSERT INTO ACCOUNT_RECEIVABLE(
               CUSTOMER_CODE      ,
               BL_NO              ,
               TYPE               ,
               RP_NO              ,
               RECEIPT_DATE       ,
               AMT                ,
               BANK               ,
               BL_DATE            ,
               CURR_CODE          ,
               RATE               ,
               AMT_F              ,
               CHEQ               ,
               REG_DATE           ,
               UPTO_DATE          ,
               PDAYS              ,
               PDESC              ,
               PBY
            )
          select
               DOH_REC.CUSTOMER_CODE  ,
               DOH_REC.DO_NO,
               1                  ,
               NULL               ,
               @BL_DATE          ,
               round(DOH_REC.AMT_O  * DOH_REC.EX_RATE,2)     ,
               NULL               ,
               dateadd(day, isnull(DOH_REC.PDAYS,0),@bl_Date),
               DOH_REC.CURR_CODE  ,
               DOH_REC.EX_RATE    ,
               DOH_REC.AMT_O      ,
               NULL               ,
               getdate()            ,
               getdate()            ,
               DOH_REC.PDAYS      ,
               DOH_REC.PDESC      ,
               DOH_REC.PBY
          from do_header DOH_REC
          where do_no = @do_no

 insert into oa_invoice_trn(
                   EB_TYPE            ,
                   COMPANY_CODE       ,
                   SECTION_CODE       ,
                   PERSON_CODE        ,
                   VENDOR_CODE        ,
                   DIST_COUNTRY_CODE  ,
                   DATA_DATE          ,
                   INVOICE_NO         ,
                   PACKING_NO         ,
                   ITEM_NO            ,
                   ITEM_TYPE          ,
                   QUANTITY           ,
                   PURCHASE_PRICE     ,
                   CURR_CODE          ,
                   EXCHANGE_RATE      ,
                   PURCHASE_AMOUNT    ,
                   PO_NO              ,
                   LINE_NO            ,
                   OPERATION_DATE     ,
                   DATA_SEQ_NO        ,
                   DATA_SOURCE_TYPE   ,
                   WAREHOUSE_CODE     ,
                   YEN_RATE           ,
                   INPORT_FLG         ,
                   PACK_QTY
               ) select
                   'E',
                   DOH_REC.CUSTOMER_CODE   ,
                   null                    ,
                   null                    ,
                   CU2_REC.COMPANY_CODE    ,
                   105                     ,
                   @BL_DATE               ,
                   substring(DOH_REC.DO_NO,1,23),
                   null          ,
                   DOS_REC.ITEM_NO         ,
                   null                    ,
                   DOS_REC.SO_QTY          ,
                   DOS_REC.U_PRICE         ,
                   DOH_REC.CURR_CODE       ,
                   null                    ,
                   null                    ,
                   DOS_REC.CUSTOMER_PO_NO  ,
                   SOD_REC.CUSTOMER_PO_LINE_NO ,
                   getdate()                 ,
                   null                    ,
                   'OA'                    ,
                   0                       ,
                   0                       ,
                   0                       ,
                   ITM_REC.EXTERNAL_UNIT_NUMBER
                from do_header DOH_REC
                inner join company CU2_REC 
                on cu2_rec.company_code = doh_rec.customer_code
                inner join do_so dos_rec
                on doh_rec.do_no = dos_rec.do_no
                inner join so_details SOD_REC
                on sod_rec.so_no = dos_rec.so_no and sod_rec.LINE_NO = dos_rec.so_line_no
                inner join item ITM_REC
                on itm_rec.item_no = sod_rec.item_no
                where DOH_REC.do_no = @do_no and COUNTRY_CODE=192

 insert into fdk_po_transit_trn(
                       COMPANY_CODE            ,
                       SECTION_CODE            ,
                       PERSON_CODE             ,
                       VENDOR_CODE             ,
                       DIST_COUNTRY_CODE       ,
                       DATA_DATE               ,
                       INVOICE_NO              ,
                       INVOICE_LINE_NO         ,
                       PACKING_NO              ,
                       PO_NO                   ,
                       PO_LINE_NO              ,
                       ITEM                    ,
                       ITEM_NO                 ,
                       QUANTITY                ,
                       PURCHASE_PRICE          ,
                       CURR_CODE               ,
                       EXCHANGE_RATE           ,
                       PURCHASE_AMOUNT         ,
                       OPERATION_DATE          ,
                       DATA_SEQ_NO             ,
                       DATA_SOURCE_TYPE        ,
                       WAREHOUSE_CODE          ,
                       YEN_RATE                ,
                       INPORT_FLG              ,
                       PACK_QTY                ,
                       SHIPTO_CODE
                   ) select 
                       DOH_REC.CUSTOMER_CODE   ,
                       null                    ,
                       null                    ,
                       CU2_REC.COMPANY_CODE    ,
                       105                     ,
                       @BL_DATE               ,
                       DOH_REC.DO_NO           ,
                       DOS_REC.LINE_NO         ,
                       null                    ,
                       DOS_REC.CUSTOMER_PO_NO  ,
                       SOD_REC.CUSTOMER_PO_LINE_NO  ,
                       SUBSTRING(ITM_REC.ITEM,1,30)           ,
                       DOS_REC.ITEM_NO         ,
                       DOS_REC.SO_QTY          ,
                       DOS_REC.U_PRICE         ,
                       DOH_REC.CURR_CODE       ,
                       null                    ,
                       SOD_REC.AMT_O           ,
                       GETDATE()                 ,
                       null                    ,
                       'PT'                    ,
                       0                       ,
                       0                       ,
                       0                       ,
                       ITM_REC.EXTERNAL_UNIT_NUMBER,
                       null
                from do_header DOH_REC
                inner join company CU2_REC 
                on cu2_rec.company_code = doh_rec.customer_code
                inner join do_so dos_rec
                on doh_rec.do_no = dos_rec.do_no
                inner join so_details SOD_REC
                on sod_rec.so_no = dos_rec.so_no and sod_rec.LINE_NO = dos_rec.so_line_no
                inner join item ITM_REC
                on itm_rec.item_no = sod_rec.item_no
                where DOH_REC.do_no = @do_no and (select top 1 config_flag from CONFIGURATION where CONFIG_KEY = 'FI_PO_TRANSIT') = 1

INSERT INTO TRANSACTION_SALES (
                  OPERATION_DATE,
                  SECTION_CODE,
                  ITEM_NO,
                  ITEM_CODE,
                  ITEM_NAME,
                  ITEM_DESCRIPTION,
                  STOCK_SUBJECT_CODE,
                  ACCOUNTING_MONTH,
                  SLIP_DATE,
                  SLIP_TYPE,
                  SLIP_NO,
                  SLIP_QUANTITY,
                  SLIP_PRICE,
                  SLIP_AMOUNT,
                  CURR_CODE,
                  STANDARD_PRICE,
                  STANDARD_AMOUNT,
                  SUPPLIERS_PRICE,
                  COMPANY_CODE,
                  ORDER_NUMBER,
                  LINE_NO,
                  COST_PROCESS_CODE,
                  COST_SUBJECT_CODE,
                  PRODUCT_LOT_NUMBER,
                  PURCHASE_QUANTITY,
                  PURCHASE_PRICE,
                  PURCHASE_AMOUNT,
                  PURCHASE_UNIT,
                  UNIT_STOCK,
                  EX_RATE,
                  ANSWER_NO,
                  REMARK1,
                  REMARK2,
                  CUSTOMER_PO_NO,
                  WO_NO,
                  DATE_CODE,
                  REMARK3,
                  INV_NO
               ) select 
                  getdate()                   ,
                  ITM_REC.SECTION_CODE      ,
                  DOS_REC.ITEM_NO           ,
                  ITM_REC.ITEM_CODE         ,
                  ITM_REC.ITEM              ,
                  substring(ITM_REC.DESCRIPTION,0,30)       ,
                  ITM_REC.STOCK_SUBJECT_CODE,
                  CONVERT(nvarchar(6), @BL_DATE, 112),
                  @BL_DATE                 ,
                  '84'                      ,
                  DOH_REC.DO_NO             ,
                  DOS_REC.QTY               ,
                  DOS_REC.U_PRICE           ,
                  round(DOS_REC.QTY * DOS_REC.U_PRICE,2),
                  DOH_REC.CURR_CODE         ,
                  ITM_REC.STANDARD_PRICE    ,
                  round(ITM_REC.STANDARD_PRICE * DOS_REC.QTY,8),
                  ITM_REC.SUPPLIERS_PRICE   ,
                  DOH_REC.CUSTOMER_CODE     ,
                  NULL                      ,
                  NULL                      ,
                  ITM_REC.COST_PROCESS_CODE ,
                  ITM_REC.COST_SUBJECT_CODE ,
                  NULL                      ,
                  NULL                      ,
                  NULL                      ,
                  NULL                      ,
                  NULL                      ,
                  ITM_REC.UNIT_STOCK        ,
                  DOH_REC.EX_RATE           ,
                  NULL                      ,
                  NULL                      ,
                  NULL                      ,
                  NULL                      ,
                  NULL                      ,
                  NULL                      ,
                  NULL                      ,
                  DOH_REC.INV_NO              
                from do_header DOH_REC
                    inner join company CU2_REC 
                    on cu2_rec.company_code = doh_rec.customer_code
                    inner join do_so dos_rec
                    on doh_rec.do_no = dos_rec.do_no
                    inner join so_details SOD_REC
                    on sod_rec.so_no = dos_rec.so_no and sod_rec.LINE_NO = dos_rec.so_line_no
                    inner join item ITM_REC
                    on itm_rec.item_no = sod_rec.item_no
                    where DOH_REC.do_no = @do_no

UPDATE DO_HEADER SET BL_DATE=@bl_date ,SHIP_END_FLG='F' WHERE DO_NO = @DO_NO 

    
COMMIT TRAN TT

GO
