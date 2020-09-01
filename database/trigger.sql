create trigger [dbo].[ZTR__WH_KANBAN_TRANS]
on [dbo].[ZTB_WH_KANBAN_TRANS] 
AFTER INSERT
AS

declare @wo_no as varchar(100),
        @plt_no as int,
        @slip_no as VARCHAR(100),
        @item_no int

select @wo_no = wo_no ,
       @plt_no = plt_no,
       @slip_no = SLIP_NO,
       @item_no = ITEM_NO
from inserted

delete ztb_item_book where wo_no = @wo_no and plt_no = @plt_no 

Insert into mte_details
(slip_no, line_no, item_no,qty,reg_date, upto_date, wo_no,remark)
select slip_no,line_no,item_no,qty,getdate(),getdate(),@wo_no,'KANBAN plt no' + cast(@plt_no as nvarchar(5)) from inserted where NOT EXISTS
(select * from mte_details where slip_no= @slip_no AND item_no= @item_no);
        
GO

create trigger [dbo].[ZTR_WH_OUT_DET]
on [dbo].[ZTB_WH_OUT_DET] 
AFTER UPDATE
AS
 
declare @print int 

select @print = [PRINT] from inserted
  
 IF @print = 1 BEGIN
      Insert into mte_header
     (Slip_no, slip_date, company_code, slip_type, display_type, section_code, person_code)
     select 'MT-'+ inserted.slip_no,inserted.WO_Date,'100001','21','C',100,'KANBAN' from inserted where NOT EXISTS
     (select * from mte_header where slip_no='MT-' + inserted.slip_no);
      
      Insert into mte_details
      (slip_no, line_no, item_no,qty,  reg_date, upto_date, wo_no,remark)
      select 'MT-'+ inserted.slip_no,1,inserted.item_no,inserted.qty,getdate(),getdate(),'MATERIAL COMMON','KANBAN' from inserted where NOT EXISTS
      (select * from mte_details where slip_no='MT-' + inserted.slip_no AND item_no= inserted.item_no);
      
 END
GO

CREATE trigger [dbo].[ZTG_WH_KANBAN_TRANS_FG]
on [dbo].[ZTB_WH_KANBAN_TRANS_FG] 
AFTER UPDATE
AS


declare @old_flag int,
        @new_flag int,
        @old_slip_no nvarchar(50),
        @item_no int,
        @slip_date date, 
        @quantity bigint, 
        @slip_type nvarchar(3) = '1',
        @wo_no nvarchar(50)
       


select @new_flag = flag from INSERTED

select @old_flag = flag,@old_slip_no = slip_no from DELETED

select @item_no = item_no, 
       @slip_date = slip_date, 
       @quantity = slip_quantity,
       @wo_no = WO_NO
from PRODUCTION_INCOME where slip_no = @old_slip_no


IF (@new_flag = 1 AND @old_flag = 0 ) BEGIN
      insert into [TRANSACTION] (operation_date,

                              section_code,

                              item_no,

                              item_code,

                              item_name,

                              item_description,

                              stock_subject_code,

                              accounting_month,

                              slip_date,

                              slip_type,

                              slip_no,

                              slip_quantity,

                              slip_price,

                              slip_amount,

                              curr_code,

                              standard_price,

                              standard_amount,

                              company_code,

                              cost_process_code ,

                              cost_subject_code,

                              unit_stock,

                              ex_rate,

                              wo_no,

                              remark3,

                              date_code,

                              customer_po_no)

      select getdate(),

             t.section_code,

             i.item_no,

             i.item_code,

             i.item,

             substring(i.description,0,30),

             i.stock_subject_code,

             (SELECT CONVERT(nvarchar(6), t.slip_date, 112)),

             t.slip_date,

             t.slip_type,

             t.slip_no,

             t.slip_quantity,

             isnull(t.standard_price,i.standard_price),

             round(isnull(t.standard_price,i.standard_price) * t.slip_quantity,6),

             isnull(t.curr_code,i.curr_code),

             i.standard_price,

             round(i.standard_price * t.slip_quantity,6),

            t.company_code,

            t.cost_process_code,

            t.cost_subject_code,

            i.unit_stock,

            dbo.get_ex_rate(t.curr_code,t.slip_date,null),

            t.wo_no,

            t.remark3,

            t.date_code,

            t.customer_po_no

            from  item i

            inner join PRODUCTION_INCOME t on i.item_no = t.item_no

            where t.slip_no = @old_slip_no and t.approval_date is null

 exec whinventory_set @item_no ,@slip_date, @quantity, @slip_type, 0, 100

 insert into ztb_kanban_delay(item_no,tanggal,qty,wo_no,plt_no, bateretype)
 values(@item_no,@slip_date, @quantity, @slip_type,0, 100);

       --update production_income

      update production_income set approval_date = getdate(), approval_person_code = 'FI0041'

      where slip_no = @old_slip_no and approval_date is null;

     

      -- update MPS_REMAIN

      update MPS_REMAIN set

        LAST_KURAIRE_DATE = getdate(),

        KURAIRE_QTY = KURAIRE_QTY + @quantity,

        BAL_QTY = QTY - @quantity

      where WORK_ORDER = @wo_no;



            -- INSERT INTO FDAC_PRODUCT_TRN(DATA_TYPE,

            --             COMPANY_CODE,

            --             CUSTOMER_CODE,

            --             ITEM_NO,

            --             WAREHOUSE_CODE,

            --             DATA_DATE,

            --             QUANTITY,

            --             TOV,

            --             CURR_CODE,

            --             AMOUNT,

            --             DATA_SOURCE_TYPE,

            --             ITEM_TYPE,

            --             CHECK_NO,

            --             OPERATION_DATE,

            --             OPERATION_TYPE,

            --             INFO_TYPE,

            --             ITEM,

            --             SRC_CLASS_CODE,

            --             SECTION_CODE,

            --             PERSON_CODE,

            --             CUSTOMER_ITEM_NO,

            --             CUSTOMER,

            --             SELL_COUNTRY_CODE,

            --             SELL_PREFECTURE_CODE,

            --             WAREHOUSE,

            --             WH_COUNTRY_CODE,

            --             WH_PREFECTURE_CODE,

            --             WAREHOUSE_TYPE)

            --     select DECODE(SIGN(0 - isnull(t.slip_quantity,0)),1,'620','610'),

            --       c.COMPANY_CODE,

            --       NULL,

            --       i.ITEM_NO,

            --       t.company_code,

            --       t.slip_date,

            --       t.slip_quantity,

            --       i.STANDARD_PRICE,

            --       i.CURR_CODE,

            --       round(isnull(i.STANDARD_PRICE,t.standard_price)*t.slip_quantity,6),

            --       f.DATA_SOURCE_TYPE,

            --       0,

            --       t.slip_no,

            --       getdate(),

            --       0,

            --       0,

            --       substring(i.DESCRIPTION,0,30),

            --       isnull(i.CLASS_CODE,0),

            --       f.SECTION_CODE,

            --       f.PERSON_CODE,

            --       NULL,

            --       NULL,

            --       NULL,

            --       NULL,

            --       w.COMPANY,

            --       w.COUNTRY_CODE,

            --       NULL,

            --       4

            --       FROM ITEM i,COMPANY c,COMPANY w,FDAC_INIT f, production_income t

            --       where t.slip_type=80

            --       and i.item_no = t.item_no --Edited By Reza Vebrian 28-08-2017, Prevent insert all item

            --       and c.COMPANY_TYPE=0

            --       and w.COMPANY_CODE = t.company_code

            --       AND t.slip_no = :OLD.SLIP_NO and t.approval_date is null;


END


GO

create trigger [dbo].[ZTR_DO_TEMP]
ON [dbo].[ZTB_DO_TEMP]
AFTER INSERT,UPDATE,DELETE
as

DECLARE @Action as char(1);
    SET @Action = (CASE WHEN EXISTS(SELECT * FROM INSERTED)
                         AND EXISTS(SELECT * FROM DELETED)
                        THEN 'U'  -- Set Action to Updated.
                        WHEN EXISTS(SELECT * FROM INSERTED)
                        THEN 'I'  -- Set Action to Insert.
                        WHEN EXISTS(SELECT * FROM DELETED)
                        THEN 'D'  -- Set Action to Deleted.
                        ELSE NULL -- Skip. It may have been a "failed delete".   
                    END)
declare @do_sts nvarchar(10) ,
        @REMARK_SHIPPING nvarchar(100),
        @inv_no nvarchar(50),
        @inv_line_no int,
        @ex_factory date,
        @answer_no nvarchar(100),
        @eta date,
        @etd date,
        @ship_name NVARCHAR(max),
        @remark nvarchar(max),
        @do_no nvarchar(100)

select @do_sts = do_sts,@REMARK_SHIPPING = REMARK_SHIPPING,
       @inv_no=DO_NO, @inv_line_no=LINE_NO, @ex_factory = DO_DATE,
       @answer_no=answer_no ,
       @eta = eta,@etd = etd, @ship_name = SHIP_NAME, @remark = REMARK
       from inserted

select @do_no = do_no 
from deleted

IF @Action = 'I' BEGIN
    IF @do_sts = 'HEADER' BEGIN
      INSERT INTO DO_HEADER(

        DO_NO,INV_NO,DO_DATE,INV_DATE,CUSTOMER_CODE,CURR_CODE,EX_RATE,DUE_DATE,PDAYS,PDESC,[DESCRIPTION],REMARK,AMT_O,AMT_L,GST_RATE,

        SHIP_NAME,PORT_LOADING,PORT_DISCHARGE,ETD,ETA,CONTRACT_SEQ,PBY,FINAL_DESTINATION,TRADE_TERM,NOTIFY,

        ATTN,ADDRESS_FLG,PERSON_CODE,PORT_LOADING_CODE,PORT_DISCHARGE_CODE,FINAL_DESTINATION_CODE,SI_NO,DO_TYPE)

      select  DO_NO,DO_NO,DO_DATE,DO_DATE,CUSTOMER_CODE,CURR_CODE,EX_RATE,DUE_DATE,PDAYS,PDESC,PPBE_NO,REMARK,AMT_O,AMT_L,GST_RATE,

             SHIP_NAME,PORT_LOADING,PORT_DISCHARGE,ETD,ETA,CONTRACT_SEQ,PBY,FINAL_DESTINATION,TRADE_TERM,NOTIFY,

             ATTN,ADDRESS_FLG,PERSON_CODE,PORT_LOADING_CODE,PORT_DISCHARGE_CODE,FINAL_DESTINATION_CODE,SI_NO,1
       from inserted

        INSERT INTO FORWARDER_LETTER (

        do_no, customer_code, forwarder_code, domestic_truck_code, answer_no, transport, entry_type, slip_no,

        booking_no, upto_date, reg_date, transport_type, cargo_type1, cargo_size1, cargo_qty1, cargo_type2, cargo_size2, cargo_qty2)

       select  DO_NO, CUSTOMER_CODE, FORWARDER_CODE, DOMESTIC_TRUCK_CODE, ANSWER_NO, TRANSPORT, ENTRY_TYPE, DO_NO,

        BOOKING_NO, getdate(), getdate(), TRANSPORT_TYPE, cargo_type1, cargo_size1, cargo_qty1, cargo_type2, cargo_size2, cargo_qty2
        from inserted

     

      -- account_receivable

      INSERT INTO ACCOUNT_RECEIVABLE (customer_code, bl_no, type, receipt_date, amt, bl_date, curr_code, rate, amt_f, reg_date, upto_date, pby, pdays, pdesc)
      select CUSTOMER_CODE, DO_NO,'1',DO_DATE, AMT_O, DUE_DATE, CURR_CODE, EX_RATE, AMT_L, getdate(), getdate(), PBY, PDAYS, PDESC
      from inserted
     


      INSERT INTO ZTB_PPBE (do_no, person_code, no, ppbe_no, period)
      select DO_NO, PERSON_CODE, cast(left(PPBE_NO,3) as int), PPBE_NO, year(getdate()) 
      from inserted;

    
    END ELSE IF @do_sts = 'DETAILS'  BEGIN
    -- insert do_details

      INSERT INTO DO_DETAILS (

        DO_NO, LINE_NO, ITEM_NO, ORIGIN_CODE, CUSTOMER_PART_NO, QTY, UOM_Q, U_PRICE, AMT_O, AMT_L,

        DESCRIPTION, SO_NO1, SO_LINE_NO1, QTY1, CUSTOMER_PO_NO1, ANSWER_NO1, REMARK2, UPTO_DATE,REG_DATE,CARVED_STAMP)
      select DO_NO, LINE_NO, item.ITEM_NO, item.ORIGIN_CODE, CUSTOMER_PART_NO, QTY, item.UOM_Q, U_PRICE, AMT_O, AMT_L,
        GOODS_NAME, SO_NO, SO_LINE_NO, QTY_ANSWER, CUSTOMER_PO_NO, ANSWER_NO, case when item_code is null then REMARK_PACKING else 'ITEM#'+' '+item_code end, getdate(), getdate(), CARVED_STAMP
      from inserted 
      inner join item 
      on item.item_no = inserted.item_no

     

      IF (@REMARK_SHIPPING is not null) BEGIN

        -- INSERT DO_MARKS

        INSERT INTO DO_MARKS (do_no, mark_no, marks) 
        select DO_NO, LINE_NO, REMARK_SHIPPING
        from inserted

        -- grpcasemark_out

        INSERT INTO GRPCASEMARK_OUT (do_no,mark_no,marks,reg_date) 
        select DO_NO, LINE_NO, REMARK_SHIPPING,getdate()
        from inserted

      END

     

      -- do_pl_heder

      INSERT INTO DO_PL_HEADER (

        DO_NO, PL_LINE_NO, CASE_NO, CASE_TOTAL, UOM_P, NET, NET_UOM, GROSS, GROSS_UOM, MEASUREMENT, QTY)
      SELECT i.DO_NO, i.LINE_NO, a.START_BOX +'-'+ a.END_BOX, a.CARTON, 100, cast(a.NW as decimal(18,3)), 30,cast(a.GW as decimal(18,3)), 30, cast(a.MSM as decimal(18,3)), i.QTY
      FROM ZTB_SHIPPING_INS a
      inner join inserted i
      on a.ANSWER_NO= i.ANSWER_NO;

     

      -- do_pl_details

      INSERT INTO DO_PL_DETAILS (

        DO_NO, PL_LINE_NO, FDK_PART, CUSTOMER_PART_NO, QTY, UOM_Q, INNER_QUANTITY, INNER_PACKAGE, INNER_UOM_P)

      SELECT i.DO_NO, i.LINE_NO, i.DESCRIPTION, i.CUSTOMER_PART_NO, bb.pallet_pcs/bb.pallet_ctn, 10, bb.pallet_pcs/bb.pallet_ctn, 1,100
      FROM ZTB_SHIPPING_INS a
      inner join ztb_item bb
      on a.item_no = bb.item_no
      inner join inserted i
      on a.ANSWER_NO= i.ANSWER_NO;

     

      -- UPDATE INDICATION

      update INDICATION set inv_no= @inv_no, inv_line_no= @inv_line_no, ex_factory = @ex_factory where answer_no = @ANSWER_NO;

     
    END 
END ELSE IF @Action = 'U' BEGIN

    update do_header set ETA = @ETA, ETD = @ETD, SHIP_NAME = @SHIP_NAME, REMARK = @REMARK WHERE do_no = @DO_NO;

END ELSE IF @Action = 'D' BEGIN
     DELETE FROM DO_HEADER WHERE DO_NO = @DO_NO;

    DELETE FROM FORWARDER_LETTER WHERE DO_NO = @DO_NO;

    DELETE FROM DO_DETAILS WHERE DO_NO = @DO_NO;

    DELETE FROM DO_MARKS WHERE DO_NO = @DO_NO;

    DELETE FROM DO_PL_HEADER WHERE DO_NO = @DO_NO;

   DELETE FROM DO_PL_DETAILS WHERE DO_NO = @DO_NO;

    DELETE FROM ACCOUNT_RECEIVABLE WHERE BL_NO = @DO_NO;

    DELETE FROM GRPCASEMARK_OUT WHERE DO_NO = @DO_NO;



    UPDATE INDICATION set inv_no = '', inv_line_no = '' WHERE answer_no = @ANSWER_NO;
END

GO

CREATE trigger [dbo].[ZTR_ANSWER_FIX]
ON [dbo].[ANSWER]
AFTER INSERT,UPDATE,DELETE
as

DECLARE @Action as char(1);
    SET @Action = (CASE WHEN EXISTS(SELECT * FROM INSERTED)
                         AND EXISTS(SELECT * FROM DELETED)
                        THEN 'U'  -- Set Action to Updated.
                        WHEN EXISTS(SELECT * FROM INSERTED)
                        THEN 'I'  -- Set Action to Insert.
                        WHEN EXISTS(SELECT * FROM DELETED)
                        THEN 'D'  -- Set Action to Deleted.
                        ELSE NULL -- Skip. It may have been a "failed delete".   
                    END)

declare @wo_no  nvarchar(30),
        @so_no  nvarchar(20),
        @so_line_no int,
        @v_qty    bigint,
        @box    decimal(10,4),
        @qty bigint,
        @item_no int,
        @crs_remark nvarchar(50),
        @answer_no nvarchar(100),
        @old_answer_no nvarchar(50),
        @old_crs_remark nvarchar(50),
        @work_no nvarchar(50),
        @origin_code nvarchar(10),
        @stuffy_date date,
        @eta date,
        @etd date,
        @vessel nvarchar(200)

select @wo_no = work_no,
       @work_no = work_no,
       @so_no = so_no,
       @so_line_no = so_line_no,
       @qty = qty,
       @item_no = item_no,
       @crs_remark = crs_remark,
       @origin_code = ORIGIN_CODE,
       @stuffy_date = STUFFY_DATE,
       @eta = eta,
       @etd = etd,
       @vessel = vessel,
       @answer_no = ANSWER_NO
from inserted

select  @old_answer_no = answer_no,
        @old_crs_remark = CRS_REMARK
from deleted


IF @Action = 'I'  BEGIN

   

    select @v_qty = isnull(sum( case when slip_type = 80 then slip_quantity else slip_quantity *-1 end ),0)  from production_income 
    where wo_no = @work_no;
    select @box=isnull(max(isnull(end_box,0)),0)  from ztb_shipping_ins where so_no =  @so_no and line_no = @so_line_no;

    

     IF  @qty <= @v_qty  begin
        insert into indication
        (operation_date, answer_no, so_no, so_line_no, item_no,origin_code, qty, ex_factory)--, remark)
         select  getdate(), @answer_no, @so_no, @so_line_no, @item_no, @origin_code, @qty, @stuffy_date
         from inserted

     END ELSE BEGIN
        insert into indication
         (operation_date, answer_no, so_no, so_line_no, item_no,origin_code, qty, ex_factory, remark)
        select  getdate(), @answer_no, @so_no, @so_line_no, @item_no, @origin_code, @qty, @stuffy_date,1
         from inserted
     END

     insert into ztb_shipping_ins (remarks,box_pcs,SO_NO,LINE_no,ITEM_NO,wo_no, answer_no,qty,gw,nw,msm,carton, carton_non_full, pallet,start_box,end_box)

     select 

           @crs_remark,

           pallet_ctn,

           @so_no,

           @so_line_no,

           @item_no,

           @work_no ,

           @answer_no ,

           sum(@qty) as pieces,

           case when sum(@qty)< pallet_pcs then

             (gw_pallet *  (floor((sum(@qty)+pallet_pcs)/pallet_pcs))

             +

             (

             ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end)/

             pallet_ctn) *  case when sum(@qty)/pallet_pcs - floor(sum(@qty)/pallet_pcs) > 0 then

             (sum(@qty) / (pallet_pcs / pallet_ctn)) - (floor(sum(@qty)/pallet_pcs) * pallet_ctn)

             else 0 end

             )+

             case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end) - gw_pallet

           else

             gw_pallet *  floor(sum(@qty)/pallet_pcs)

             +

             case when sum(@qty)/pallet_pcs - floor(sum(@qty)/pallet_pcs) > 0 then

               (

               ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end 

               ) / pallet_ctn) *

                 case when sum(@qty)/pallet_pcs - floor(sum(@qty)/pallet_pcs) > 0 then

                 (sum(@qty) / (pallet_pcs / pallet_ctn)) - (floor(sum(@qty)/pallet_pcs) * pallet_ctn)

                 else 0 end

               )

               +case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end

             else 0 end

           end  as GrossWeight,

           

           sum(@qty) * zt.NW_Pallet / pallet_pcs  as NetWeight,


                 floor(sum(@qty)/zt.pallet_pcs) * zt.panjang_pallet * zt.lebar_pallet * (zt.carton_height+150) / 10000000

                 +

                 (case when sum(@qty)%zt.pallet_pcs > 0 then

                   (

                   (floor(

                     (ceiling(

                       ceiling(

                         sum(@qty) % zt.pallet_pcs/(zt.pallet_pcs/ zt.pallet_ctn)

                       )/(zt.pallet_ctn/zt.step)

                     )*(zt.carton_height/zt.step))+150

                   ) * zt.panjang_pallet * zt.lebar_pallet) / 10000000

                   )

                  else 0 end

                 )

                 as MSM,

           -- END UENG

           

           sum(@qty)/(pallet_pcs/pallet_ctn) as box,

           

           case when sum(@qty)/pallet_pcs - floor(sum(@qty)/pallet_pcs) > 0 then

             (sum(@qty) / (pallet_pcs / pallet_ctn)) - (floor(sum(@qty)/pallet_pcs) * pallet_ctn)

           else 0 end,

            

           cast(sum(@qty)/pallet_pcs as decimal(18,4)) as pallet,

           cast(case when @box = 0  then 1 else @box + 1 end as decimal(18,4)),

           cast(case when @box = 0  then sum(@qty)/(pallet_pcs/pallet_ctn) else @box + sum(@qty)/(pallet_pcs/pallet_ctn) end as decimal(18,4))

     from item i

     left outer join packing_information pi on pi.pi_no = i.pi_no

     left outer join ztb_item zt on zt.item_no = i.item_no

     where  i.item_no = @item_no

     group by pallet_pcs, pallet_ctn,zt.GW_Pallet, zt.NW_Pallet, ZT.Step, ZT.Carton_Height, i.external_unit_number,

     ZT.Panjang_Pallet,ZT.Lebar_Pallet;

     exec ZSP_SHIP_DETAIL @item_no,@qty,@crs_remark,@work_no,@answer_no;

END 

IF @Action = 'U'  BEGIN
     declare @commit date
     select @commit = commit_date  from indication where answer_no = @ANSWER_NO;

     IF @commit is NOT NULL BEGIN

       update ztb_shipping_plan set ETA = @ETA, ETD = @ETD, VESSEL = @VESSEL  WHERE inv_no= @CRS_REMARK;

     END ELSE BEGIN

         --delete from indication where answer_no=:OLD.ANSWER_NO ;

         delete from ztb_shipping_ins where answer_no=@ANSWER_NO ;

         delete ztb_shipping_detail where answer_no= @ANSWER_NO ;

         select @qty = isnull(sum( case when slip_type = 80 then slip_quantity else slip_quantity *-1 end ),0)  from production_income where wo_no = @work_no;

         select @box = isnull(max(isnull(end_box,0)),0)  from ztb_shipping_ins where so_no =  @so_no and line_no = @so_line_no;

      END

      IF  @qty <= @v_qty  BEGIN

           update indication

           set operation_date = getdate(),

               so_no = @so_no,

               so_line_no = @so_line_no,

               item_no = @item_no,

               origin_code = @origin_code,

               qty = @qty,

               ex_factory = @stuffy_date,

               remark = null

           where answer_no = @answer_no;

         END else BEGIN

           update indication

           set operation_date = getdate(),

               so_no = @so_no,

               so_line_no = @so_line_no,

               item_no = @item_no,

               origin_code = @origin_code,

               qty = @qty,

               ex_factory = @stuffy_date,

               remark = 1

            where answer_no = @answer_no;

         end;

    insert into ztb_shipping_ins (remarks,box_pcs,SO_NO,LINE_no,ITEM_NO,wo_no, answer_no,qty,gw,nw,msm,carton, carton_non_full, pallet,start_box,end_box)

         select 

              @crs_remark,

              pallet_ctn,

              @so_no,

              @so_line_no,

              @item_no,

              @work_no ,

              @answer_no ,

              sum(@qty) as pieces,

              case when sum(@qty)< pallet_pcs then

             (gw_pallet *  (floor((sum(@qty)+pallet_pcs)/pallet_pcs))

               +

             

                 (

                 ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end 

                 ) / pallet_ctn) *  case when sum(@qty)/pallet_pcs - floor(sum(@qty)/pallet_pcs) > 0 then

                   (sum(@qty) / (pallet_pcs / pallet_ctn)) - (floor(sum(@qty)/pallet_pcs) * pallet_ctn)

                   else 0 end )

                 +case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end)

      

               - gw_pallet

              else

              gw_pallet *  floor(sum(@qty)/pallet_pcs)

               +

               case when sum(@qty)/pallet_pcs - floor(sum(@qty)/pallet_pcs) > 0 then

                 (

                 ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end 

                 ) / pallet_ctn) *

                   case when sum(@qty)/pallet_pcs - floor(sum(@qty)/pallet_pcs) > 0 then

                   (sum(@qty) / (pallet_pcs / pallet_ctn)) - (floor(sum(@qty)/pallet_pcs) * pallet_ctn)

                   else 0 end

                 )

                 +case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end

               else 0

               end

               end  as GrossWeight,

               

              sum(@qty) * zt.NW_Pallet / pallet_pcs  as NetWeight,

     

                 floor(sum(@qty)/zt.pallet_pcs) * zt.panjang_pallet * zt.lebar_pallet * (zt.carton_height+150) / 10000000

                 +

                 (case when sum(@qty)%zt.pallet_pcs > 0 then

                   (

                   (floor(

                     (ceiling(

                       ceiling(

                         sum(@qty) % zt.pallet_pcs/(zt.pallet_pcs/ zt.pallet_ctn)

                       )/(zt.pallet_ctn/zt.step)

                     )*(zt.carton_height/zt.step))+150

                   ) * zt.panjang_pallet * zt.lebar_pallet) / 10000000

                   )

                  else 0 end

                 )

                 as MSM,

             -- END UENG

              sum(@qty)/(pallet_pcs/pallet_ctn) as box,

             case when sum(@qty)/pallet_pcs - floor(sum(@qty)/pallet_pcs) > 0 then

             (sum(@qty) / (pallet_pcs / pallet_ctn)) - (floor(sum(@qty)/pallet_pcs) * pallet_ctn)

             else 0 end,

              cast(sum(@qty)/pallet_pcs as decimal(18,4)) as pallet,

              cast(case when @box = 0  then 1 else @box + 1 end as decimal(18,4)),

              cast(case when @box = 0  then sum(@qty)/(pallet_pcs/pallet_ctn) else @box + sum(@qty)/(pallet_pcs/pallet_ctn) end as decimal(18,4))

         from item i

         left outer join packing_information pi on pi.pi_no = i.pi_no

         left outer join ztb_item zt on zt.item_no = i.item_no

         where  i.item_no = @item_no group by pallet_pcs,pallet_ctn,zt.GW_Pallet,zt.NW_Pallet,ZT.Step ,

         ZT.Carton_Height,i.external_unit_number ,ZT.Panjang_Pallet,ZT.Lebar_Pallet;

 

         EXEC ZSP_SHIP_DETAIL @item_no,@qty,@crs_remark,@work_no,@answer_no
END

IF @Action = 'D' BEGIN

     delete from indication where answer_no= @old_answer_no ;

     delete from ztb_shipping_ins where answer_no= @old_answer_no ;

     delete ztb_shipping_detail where answer_no= @old_answer_no ;
END