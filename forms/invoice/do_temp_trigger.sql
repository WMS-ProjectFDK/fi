
create trigger ZTR_DO_TEMP
ON ZTB_DO_TEMP
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
--   ELSIF deleting  then

--    

--   END IF;





-- REFERENCING OLD AS OLD NEW AS NEW

-- FOR EACH ROW

-- DECLARE

--   BEGIN

--   IF inserting  then

--     IF (:NEW.DO_STS = 'HEADER') THEN

--       -- insert do_header

--       INSERT INTO DO_HEADER(

--         DO_NO,INV_NO,DO_DATE,INV_DATE,CUSTOMER_CODE,CURR_CODE,EX_RATE,DUE_DATE,PDAYS,PDESC,DESCRIPTION,REMARK,AMT_O,AMT_L,GST_RATE,

--         SHIP_NAME,PORT_LOADING,PORT_DISCHARGE,ETD,ETA,CONTRACT_SEQ,PBY,FINAL_DESTINATION,TRADE_TERM,NOTIFY,

--         ATTN,ADDRESS_FLG,PERSON_CODE,PORT_LOADING_CODE,PORT_DISCHARGE_CODE,FINAL_DESTINATION_CODE,SI_NO,DO_TYPE)

--       VALUES(:NEW.DO_NO,:NEW.DO_NO,:NEW.DO_DATE,:NEW.DO_DATE,:NEW.CUSTOMER_CODE,:NEW.CURR_CODE,:NEW.EX_RATE,:NEW.DUE_DATE,:NEW.PDAYS,:NEW.PDESC,:NEW.PPBE_NO,:NEW.REMARK,:NEW.AMT_O,:NEW.AMT_L,:NEW.GST_RATE,

--              :NEW.SHIP_NAME,:NEW.PORT_LOADING,:NEW.PORT_DISCHARGE,:NEW.ETD,:NEW.ETA,:NEW.CONTRACT_SEQ,:NEW.PBY,:NEW.FINAL_DESTINATION,:NEW.TRADE_TERM,:NEW.NOTIFY,

--              :NEW.ATTN,:NEW.ADDRESS_FLG,:NEW.PERSON_CODE,:NEW.PORT_LOADING_CODE,:NEW.PORT_DISCHARGE_CODE,:NEW.FINAL_DESTINATION_CODE,:NEW.SI_NO,1);

    

--       -- forwarder_letter

--       INSERT INTO FORWARDER_LETTER (

--         do_no, customer_code, forwarder_code, domestic_truck_code, answer_no, transport, entry_type, slip_no,

--         booking_no, upto_date, reg_date, transport_type, cargo_type1, cargo_size1, cargo_qty1, cargo_type2, cargo_size2, cargo_qty2)

--       VALUES(:NEW.DO_NO, :NEW.CUSTOMER_CODE, :NEW.FORWARDER_CODE, :NEW.DOMESTIC_TRUCK_CODE, :NEW.ANSWER_NO, :NEW.TRANSPORT, :NEW.ENTRY_TYPE, :NEW.DO_NO,

--         :NEW.BOOKING_NO, sysdate, sysdate, :NEW.TRANSPORT_TYPE, :NEW.cargo_type1, :NEW.cargo_size1, :NEW.cargo_qty1, :NEW.cargo_type2, :NEW.cargo_size2, :NEW.cargo_qty2);

     

--       -- account_receivable

--       INSERT INTO ACCOUNT_RECEIVABLE (customer_code, bl_no, type, receipt_date, amt, bl_date, curr_code, rate, amt_f, reg_date, upto_date, pby, pdays, pdesc)

--       VALUES(:NEW.CUSTOMER_CODE, :NEW.DO_NO,'1',:NEW.DO_DATE, :NEW.AMT_O, :NEW.DUE_DATE, :NEW.CURR_CODE, :NEW.EX_RATE, :NEW.AMT_L, sysdate, sysdate, :NEW.PBY, :NEW.PDAYS, :NEW.PDESC);

     

--       -- ztb_ppbe

--       --INSERT INTO ZTB_PPBE (do_no, person_code, no, ppbe_no, period)

--       --select :NEW.DO_NO, :NEW.PERSON_CODE, substr(:NEW.PPBE_NO, 1, Instr(:NEW.PPBE_NO, '/', -1, 1) -1), :NEW.PPBE_NO, to_char(sysdate(),'YYYY') from dual ;

--     ELSIF(:NEW.DO_STS = 'DETAILS') THEN

--       -- insert do_details

--       INSERT INTO DO_DETAILS (

--         DO_NO, LINE_NO, ITEM_NO, ORIGIN_CODE, CUSTOMER_PART_NO, QTY, UOM_Q, U_PRICE, AMT_O, AMT_L,

--         DESCRIPTION, SO_NO1, SO_LINE_NO1, QTY1, CUSTOMER_PO_NO1, ANSWER_NO1, REMARK2, UPTO_DATE,REG_DATE,CARVED_STAMP)

--       select :NEW.DO_NO, :NEW.LINE_NO, :NEW.ITEM_NO, :NEW.ORIGIN_CODE, :NEW.CUSTOMER_PART_NO, :NEW.QTY, :NEW.UOM_Q, :NEW.U_PRICE, :NEW.AMT_O, :NEW.AMT_L,

--         :NEW.GOODS_NAME, :NEW.SO_NO, :NEW.SO_LINE_NO, :NEW.QTY_ANSWER, :NEW.CUSTOMER_PO_NO, :NEW.ANSWER_NO, case when item_code is null then :NEW.REMARK_PACKING else 'ITEM#'||' '||item_code end, sysdate, sysdate, :NEW.CARVED_STAMP

--       from item where item_no = :NEW.ITEM_NO;

     

--       IF (:NEW.REMARK_SHIPPING is not null) THEN

--         -- INSERT DO_MARKS

--         INSERT INTO DO_MARKS (do_no, mark_no, marks) VALUES (:NEW.DO_NO, :NEW.LINE_NO, :NEW.REMARK_SHIPPING);

--         -- grpcasemark_out

--         INSERT INTO GRPCASEMARK_OUT (do_no,mark_no,marks,reg_date) VALUES(:NEW.DO_NO, :NEW.LINE_NO, :NEW.REMARK_SHIPPING, sysdate);

--       END IF;

     

--       -- do_pl_heder

--       INSERT INTO DO_PL_HEADER (

--         DO_NO, PL_LINE_NO, CASE_NO, CASE_TOTAL, UOM_P, NET, NET_UOM, GROSS, GROSS_UOM, MEASUREMENT, QTY)

--       SELECT :NEW.DO_NO, :NEW.LINE_NO, a.START_BOX ||'-'|| a.END_BOX, a.CARTON, 100, cast(a.NW as decimal(18,3)), 30,cast(a.GW as decimal(18,3)), 30, cast(a.MSM as decimal(18,3)), :NEW.QTY

--       FROM ZTB_SHIPPING_INS a

--       WHERE ANSWER_NO= :NEW.ANSWER_NO;

     

--       -- do_pl_details

--       INSERT INTO DO_PL_DETAILS (

--         DO_NO, PL_LINE_NO, FDK_PART, CUSTOMER_PART_NO, QTY, UOM_Q, INNER_QUANTITY, INNER_PACKAGE, INNER_UOM_P)

--       SELECT :NEW.DO_NO, :NEW.LINE_NO, :NEW.DESCRIPTION, :NEW.CUSTOMER_PART_NO, bb.pallet_pcs/bb.pallet_ctn, 10, bb.pallet_pcs/bb.pallet_ctn, 1,100

--       FROM ZTB_SHIPPING_INS a

--       inner join ztb_item bb

--       on a.item_no = bb.item_no

--       WHERE ANSWER_NO= :NEW.ANSWER_NO;

     

--       -- UPDATE INDICATION

--       update INDICATION set inv_no=:NEW.DO_NO, inv_line_no=:NEW.LINE_NO, ex_factory = :NEW.DO_DATE where answer_no = :NEW.ANSWER_NO;

     

--     END IF;

--   ELSIF updating THEN

--     update do_header set ETA = :NEW.ETA, ETD = :NEW.ETD, SHIP_NAME = :NEW.SHIP_NAME, REMARK = :NEW.REMARK WHERE do_no = :OLD.DO_NO;

--   ELSIF deleting  then

--     DELETE FROM DO_HEADER WHERE DO_NO = :OLD.DO_NO;

--     DELETE FROM FORWARDER_LETTER WHERE DO_NO = :OLD.DO_NO;

--     DELETE FROM DO_DETAILS WHERE DO_NO = :OLD.DO_NO;

--     DELETE FROM DO_MARKS WHERE DO_NO = :OLD.DO_NO;

--     DELETE FROM DO_PL_HEADER WHERE DO_NO = :OLD.DO_NO;

--    DELETE FROM DO_PL_DETAILS WHERE DO_NO = :OLD.DO_NO;

--     DELETE FROM ACCOUNT_RECEIVABLE WHERE BL_NO = :OLD.DO_NO;

--     DELETE FROM GRPCASEMARK_OUT WHERE DO_NO = :OLD.DO_NO;

--     --DELETE from ZTB_PPBE where do_no=:OLD.DO_NO;

--     UPDATE INDICATION set inv_no = '', inv_line_no = '' WHERE answer_no = :OLD.ANSWER_NO;

--   END IF;

-- END;