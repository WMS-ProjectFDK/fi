 
 
 create procedure DELIVERY_UPDATE (
     @ans_no nvarchar(30),
     @container_no nvarchar(100),
     @seal_no nvarchar(10)
 )as

BEGIN TRANSACTION tt

 declare  @slip_type nvarchar(5) = '84'

 INSERT INTO [TRANSACTION] (
           OPERATION_DATE            ,
           SECTION_CODE              ,
           ITEM_NO                   ,
           ITEM_CODE                 ,
           ITEM_NAME                 ,
           ITEM_DESCRIPTION          ,
           STOCK_SUBJECT_CODE        ,
           ACCOUNTING_MONTH          ,
           SLIP_DATE                 ,
           SLIP_TYPE                 ,
           SLIP_NO                   ,
           SLIP_QUANTITY             ,
           SLIP_PRICE                ,
           SLIP_AMOUNT               ,
           CURR_CODE                 ,
           STANDARD_PRICE            ,
           STANDARD_AMOUNT           ,
           SUPPLIERS_PRICE           ,
           COMPANY_CODE              ,
           ORDER_NUMBER              ,
           LINE_NO                   ,
           COST_PROCESS_CODE         ,
           COST_SUBJECT_CODE         ,
           PRODUCT_LOT_NUMBER        ,
           PURCHASE_QUANTITY         ,
           PURCHASE_PRICE            ,
           PURCHASE_AMOUNT           ,
           PURCHASE_UNIT             ,
           UNIT_STOCK                ,
           EX_RATE                   ,
           ANSWER_NO
      ) 
        select      getdate()                   ,
                    ITM_REC.SECTION_CODE      ,
                    SOD_REC.ITEM_NO           ,
                    ITM_REC.ITEM_CODE         ,
                    ITM_REC.ITEM              ,
                    substring(ITM_REC.DESCRIPTION,0,30)      ,
                    ITM_REC.STOCK_SUBJECT_CODE,
                    CONVERT(nvarchar(6), IND_REC.EX_FACTORY, 112),
                    IND_REC.EX_FACTORY        ,
                    @slip_type               ,
                    isnull(DOH_REC.DO_NO,IND_REC.ANSWER_NO)                 ,
                    round(IND_REC.QTY,4),
                    round(isnull(DOD_REC.U_PRICE,0),4),
                    round(IND_REC.QTY * SOD_REC.U_PRICE,2),
                    SOH_REC.CURR_CODE         ,
                    ITM_REC.STANDARD_PRICE    ,
                    round(ITM_REC.STANDARD_PRICE * IND_REC.QTY,8),
                    ITM_REC.SUPPLIERS_PRICE   ,
                    SOH_REC.CUSTOMER_CODE     ,
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
                    SOH_REC.EX_RATE           ,
                    IND_REC.ANSWER_NO
                    from indication ind_rec,
                            do_so      dod_rec,
                            do_header  doh_rec,
                            so_header  soh_rec,
                            so_details sod_rec,
                            company  c, 
                            unit     un, 
                            item     itm_rec  
                        where ind_rec.ANSWER_NO = @ans_no
                        and ind_rec.answer_no = dod_rec.answer_no  
                        and dod_rec.do_no = doh_rec.do_no  
                        and ind_rec.so_no = sod_rec.so_no  
                        and ind_rec.so_line_no = sod_rec.line_no  
                        and sod_rec.so_no = soh_rec.so_no  
                        and sod_rec.item_no = itm_rec.item_no  
                        and itm_rec.uom_q = un.unit_code  
                        and soh_rec.customer_code = c.company_code  
     
declare @item_no int,
        @ex_factory date,
        @qty bigint,
        @sectionCode int,
        @so_no NVARCHAR(20),
        @so_line_no int

select @item_no = indication.ITEM_NO,
       @ex_factory = EX_FACTORY,
       @qty = QTY,
       @sectionCode = item.SECTION_CODE,
       @so_no = SO_NO,
       @so_line_no = SO_LINE_NO
from INDICATION
inner join item
on INDICATION.ITEM_NO = item.ITEM_NO
where ANSWER_NO = @ans_no

exec  WHINVENTORY_SET @item_no,@ex_factory,@qty,3,0,@sectionCode

UPDATE SO_DETAILS SET
    BAL_QTY = BAL_QTY - @qty
WHERE SO_NO = @so_no AND LINE_NO = @so_line_no ;

      /* SO_DELIVERY?????  2013/11/06 Y.Hagai Add */
       --PROC_SO_DELIVERY_BAL(IND_REC.SO_NO,IND_REC.SO_LINE_NO);

UPDATE ANSWER SET BAL_QTY = BAL_QTY - @qty
WHERE ANSWER_NO = @ans_no ;


UPDATE INDICATION SET
    SLIP_TYPE  = @slip_type,
    SLIP_DATE  = @ex_factory,
    COMMIT_DATE= getdate(),
    SEAL_NO = @seal_no,
    CONTAINER_NO = @container_no
WHERE ANSWER_NO = @ans_no ;
       
commit TRAN tt

GO

create procedure DELIVERY_RESTORE (
     @ans_no nvarchar(30)
 )as 

BEGIN TRAN tt

 delete from [TRANSACTION] where ANSWER_NO = @ans_no



declare @item_no int,
        @ex_factory date,
        @qty bigint,
        @sectionCode int,
        @so_no NVARCHAR(20),
        @so_line_no int,
        @minusQty bigint

select @item_no = indication.ITEM_NO,
       @ex_factory = EX_FACTORY,
       @qty = QTY,
       @minusQty = Qty *-1,
       @sectionCode = item.SECTION_CODE,
       @so_no = SO_NO,
       @so_line_no = SO_LINE_NO
from INDICATION
inner join item
on INDICATION.ITEM_NO = item.ITEM_NO
where ANSWER_NO = @ans_no



exec  WHINVENTORY_SET @item_no,@ex_factory,@minusQty,3,0,@sectionCode

 UPDATE ANSWER SET BAL_QTY = BAL_QTY + @qty
WHERE ANSWER_NO = @ans_no ;

     /* SO_DETAILS?? */
       UPDATE SO_DETAILS SET BAL_QTY = BAL_QTY + @qty
       WHERE SO_NO   = @so_no
         AND LINE_NO = @so_line_no ;

      /* SO_DELIVERY?????  2013/11/06 Y.Hagai Add */
      -- PROC_SO_DELIVERY_BAL(IND_REC.SO_NO,IND_REC.SO_LINE_NO);

     /* COMMIT DATE???????  */
        UPDATE INDICATION SET
           SLIP_DATE   = null,
           SLIP_TYPE     = null,
           COMMIT_DATE = null,
           CONTAINER_NO = null,
           SEAL_NO = null
        WHERE ANSWER_NO = @ans_no ;

commit TRAN tt
