  
create PROCEDURE "ZSP_UPDATE_PO" (
    @FROM int,
    @TO int,
    @PO_NO nvarchar(200),
    @PO_DATE nvarchar(200),
    @DI_OUTPUT_TYPE nvarchar(200),
    @TRANSPORT nvarchar(200), 
    @REMARK1 nvarchar(200),
    @MARKS1 nvarchar(200),
     @po_rev nvarchar(200),
    @po_re@res nvarchar(200),
     @EX_RATE  nvarchar(200), 
     @po_line_new   nvarchar(10),
       @ITEM_NO nvarchar(200),
     @po_line nvarchar(200),
      @UOM_Q nvarchar(200),
       @ORIGIN_CODE nvarchar(200),
       @U_PRICE nvarchar(200),
     @PO_CURR nvarchar(200),
     @PO_CURR_ITEM nvarchar(200),
     @QTY nvarchar(200),
     @GR_QTY nvarchar(200),
       @BAL_QTY nvarchar(200),
        @ETA nvarchar(200),
         @PRF_NO nvarchar(200), 
    @PRF_LINE_NO nvarchar(200),
   @PO_DT_CODE nvarchar(200),
    @D_AMT_O nvarchar(200),
    @D_AMT_L nvarchar(200),
    @PRC_UBAH nvarchar(200)
     )as
     
-- @hitungPO number :=0;  
declare @POAMTO bigint;
declare @POAMTL bigint;
declare @line_parsial int; 

-- begin


IF @po_line = 'NEW' BEGIN 
  insert into po_details(po_no, 
                             line_no,
                             prf_no,
                             prf_line_no,
                             item_no,
                             origin_code,
                             qty,
                             uom_q,
                             u_price,
                             amt_o,
                             amt_l,
                             eta,
                             schedule,
                             gr_qty,
                             sh_qty,
                             pret_qty,
                             bal_qty,
                             upto_date,
                             reg_date
                             )
  values (@po_no,
          @po_line_new,
          @prf_no,
          @prf_line_no,
          @item_no,
          @origin_code,
          @qty,
          @uom_q,
          @u_price,
          @d_amt_o,
          @d_amt_l,
          @eta,
          @eta,
          0,
          0,
          0,
          @qty,
          getdate(),
          getdate());
END ELSE IF @po_line = 'PARSIAL' BEGIN          
  select  @line_parsial = count(*)+1  from po_details where po_no= @po_no;
  insert into po_details(po_no, 
                             line_no,
                             prf_no,
                             prf_line_no,
                             item_no,
                             origin_code,
                             qty,
                             uom_q,
                             u_price,
                             amt_o,
                             amt_l,
                             eta,
                             schedule,
                             gr_qty,
                             sh_qty,
                             pret_qty,
                             bal_qty,
                             upto_date,
                             reg_date
                             )
  values (@po_no,
          @line_parsial,
          @prf_no,
          @prf_line_no,
          @item_no,
          @origin_code,
          @qty,
          @uom_q,
          @u_price,
          @d_amt_o,
          @d_amt_l,
          @eta,
          @eta,
          0,
          0,
          0,
          @qty,
          getdate(),
          getdate());          
END ELSE BEGIN
  
  update po_details 
  set qty = @qty,
      bal_qty = @bal_qty,
      u_price = @u_price,
      amt_o = @d_amt_o,
      amt_l = @d_amt_l,
      eta = @eta,
      schedule = @eta,
      upto_date = getdate(),
      carved_stamp = @PO_DT_CODE
  where line_no = @po_line and po_no = @po_no;
END 

--
update PRF_DETAILS set REMAINDER_QTY = QTY - @qty, UPTO_DATE = getdate()
where PRF_NO = @prf_no and LINE_NO = @prf_line_no;

select @POAMTl = sum(amt_l) from po_details where po_no = @po_no;
select @POAMTo  = sum(amt_o)  from po_details where po_no = @po_no;
--
update po_header set 
      po_date        = @po_date,
      amt_o 		     = @POAMTo,					  	  
      amt_l 		     = @POAMTl,						      
      di_output_type = @di_output_type,
      transport      = @transport,
      remark1        = @remark1,
      marks1         = @marks1,
      revise         = @po_rev,
      reason1        = @po_re@res,
      -- start --update ueng (25-aug-17)
      curr_code      = @po_curr,
      ex_rate        = @ex_rate,
      -- end
      upto_date       = getdate()   
	 where po_no = @po_no;


IF @PRC_UBAH = 'UPD' BEGIN
  
  declare @amt_o bigint
  declare @amt_l bigint
  declare @this_month nvarchar(10)
  
  select distinct @this_month =  this_month from whinventory
  

  --update gr_details
  update gr_details set u_price=@u_price, amt_o=@u_price*qty, amt_l=@u_price* qty* @EX_RATE
  where po_no=@po_no AND po_line_no=@po_line 
  AND gr_no  in (select gr_no from gr_header where gr_date = @this_month) 
  

  --update gr_header
  update gr_header set amt_o=(select sum(amt_o) from gr_details where gr_details.gr_no = gr_header.gr_no),
  amt_l=(select sum(amt_l) from gr_details where gr_details.gr_no = gr_header.gr_no )
  where gr_no in(select gr_no from gr_details where po_no=@po_no AND po_line_no= @po_line)
  AND gr_date = @this_month
  

  --update AP
  update account_payable set amt=(select sum(amt_o) from gr_details where gr_details.gr_no= account_payable.gr_no ),
  amt_f=(select sum(amt_l) from gr_details where gr_details.gr_no= account_payable.gr_no )
  where gr_no in(select gr_no from gr_details where po_no=@po_no AND po_line_no= @po_line)
  AND payment_date = @this_month
  

  --update FDAC
  update fdac_purchase_trn set 
  purchase_amount=(select sum(amt_o) from gr_details where gr_details.gr_no= fdac_purchase_trn.INVOICE_NO)
  where po_no= @po_no AND line_no= @po_line
  AND OPERATION_DATE = @this_month
 

  --update "transaction"
  update "transaction" set slip_price= @u_price, 
  slip_amount= (select sum(amt_l) from gr_details where gr_details.gr_no= "transaction".slip_no),
  standard_amount= (select sum(amt_l) from gr_details where gr_details.gr_no= "transaction".slip_no),
  purchase_amount= (select sum(amt_l) from gr_details where gr_details.gr_no= "transaction".slip_no)
  where order_number = @po_no AND line_no= @po_line

  end

  GO

  create procedure ZSP_INSERT_PO (
    @PO_NO  nvarchar(20),
    @SUPPLIER_CODE nvarchar(50),
    @PO_DATE  date,
    @CURR_CODE nvarchar(50), 
    @EX_RATE  nvarchar(50), 
    @TTERM nvarchar(50),
    @PDAYS nvarchar(50),
    @PDESC nvarchar(50),
    @REQ nvarchar(50),
    @REMARK1 nvarchar(50),
    @MARKS1 nvarchar(50),
    @ATTN nvarchar(50),
    @PERSON_CODE nvarchar(50),
    @ITEM_NO nvarchar(50),
    @PBY nvarchar(50),
    @SHIPTO_CODE nvarchar(50),
    @TRANSPORT nvarchar(50),
    @DI_OUTPUT_TYPE nvarchar(50),
    @PRF_NO nvarchar(50), 
    @PRF_LINE_NO nvarchar(50),
    @ORIGIN_CODE nvarchar(50),
    @QTY nvarchar(50),
    @UOM_Q nvarchar(50),
    @U_PRICE nvarchar(50),
    @D_AMT_O nvarchar(50),
    @D_AMT_L nvarchar(50), 
    @ETA nvarchar(50),
    @SCHEDULE nvarchar(50),
    @BAL_QTY nvarchar(50),
    @CARVED_STAMP nvarchar(50),
    @FROM int,
    @TO int 
)as


delete from po_details where po_no = @po_no  and line_no = @from

insert into po_details (po_no,
                       line_no,
                       prf_no,
                      prf_line_no,
                        item_no,
                        origin_code,
                        qty,
                        uom_q,
                        u_price,
                        amt_o ,
                        amt_l,
                        eta,
                        schedule,
                        gr_qty,
                        sh_qty,
                        pret_qty,
                        bal_qty,
                        upto_date,
                        reg_date,
                        carved_stamp
)values ( @po_no ,
           @from ,
             @prf_no,
         @prf_line_no,
         @item_no,
          @origin_code,
          @qty,
          @uom_q,
          @u_price,
          @d_amt_o ,
          @d_amt_l,
          @eta,
          @schedule,
          0,
          0,
          0,
          @bal_qty,
          getdate(),
          getdate(),
          @carved_stamp
);

update PRF_DETAILS set REMAINDER_QTY = QTY - @QTY, UPTO_DATE = getdate()	where PRF_NO = @prf_no and LINE_NO = @prf_line_no;

IF EXISTS(select isnull(count(po_no),0) from po_details where po_no = @po_no) BEGIN
    insert into po_header (supplier_code, 
                             po_no,
                             po_date,
                             curr_code,
                             ex_rate,
                             tterm,
                             pdays,
                             pdesc,
                             req, 
                             remark1,
                             marks1,
                             attn,
                             person_code,
                             pby,
                             upto_Date,
                             reg_date,
                             section_code,
                             shipto_code,
                             transport,
                             di_output_type
                             ) 
  values (@supplier_code,
          @po_no,
          @po_date,
          @curr_code,
          @ex_rate,
          @tterm,
          @pdays,
          @pdesc, 
          @req,
          @MARKS1 ,
          @REMARK1,
          @attn,
          @person_code,
          @tterm,
          getdate(),
          getdate(),
          100,
          @shipto_code,
          @transport,
          @di_output_type
          );

END

update po_header set amt_o = isnull(amt_o,0) + @d_amt_o,amt_l = isnull(amt_l,0) + @d_amt_l where po_no = @po_no;


