  



create PROCEDURE "ZSP_UPDATE_PO" (
    @FROM int  null,
    @TO int  null,
    @PO_NO nvarchar(200)  null,
    @PO_DATE date  null,
    @DI_OUTPUT_TYPE int  null,
    @TRANSPORT int  null, 
    @REMARK1 nvarchar(200)  null,
    @MARKS1 nvarchar(200)  null,
     @po_rev nvarchar(200)  null,
    @po_rev_res nvarchar(200)  null,
     @EX_RATE  decimal(14,6)  null,  
     @po_line_new   nvarchar(10)  null,
       @ITEM_NO int  null,
     @po_line NVARCHAR(5)  null,
      @UOM_Q nvarchar(200)  null,
       @ORIGIN_CODE nvarchar(200)  null,
       @U_PRICE decimal(14, 6)  null,
     @PO_CURR int  null,
     @PO_CURR_ITEM nvarchar(20)  null,
     @QTY decimal(11, 3)  null,
     @GR_QTY decimal(11, 3)  null,
       @BAL_QTY decimal(11, 3) null,
        @ETA date  null,
         @PRF_NO nvarchar(200)  null, 
    @PRF_LINE_NO int null,
   @PO_DT_CODE nvarchar(200)  null,
    @D_AMT_O decimal(14, 6)  null,
    @D_AMT_L decimal(14, 6)  null,
    @PRC_UBAH nvarchar(200)  null
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
          (select  count(*)+1  from po_details where po_no= @po_no),
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
      reason1        = @po_rev_res,
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
--exec ZSP_INSERT_PO '123'

alter procedure ZSP_INSERT_PO (
     @PO_NO  nvarchar(20) null,
    @SUPPLIER_CODE nvarchar(50) null,
    @PO_DATE  date null,
    @CURR_CODE int null, 
    @EX_RATE  decimal(14,6) null, 
    @TTERM nvarchar(50) null,
    @PDAYS int null,
    @PDESC nvarchar(50) null,
    @REQ nvarchar(50) null,
    @REMARK1 nvarchar(50) null,
    @MARKS1 nvarchar(50) null,
    @ATTN nvarchar(50) null,
    @PERSON_CODE nvarchar(50)null,
    @PBY nvarchar(50)null,
    @ITEM_NO int null,
    @SHIPTO_CODE int null,
    @TRANSPORT int,
    @DI_OUTPUT_TYPE int null,
    @PRF_NO nvarchar(50)null,
    @PRF_LINE_NO int null,
    @ORIGIN_CODE int null,
    @QTY decimal(11, 3) null,
    @UOM_Q varchar(3) null,
    @U_PRICE decimal(14, 6) null,
    @D_AMT_O decimal(14, 2) null,
    @D_AMT_L decimal(14, 2) null, 
    @ETA date null,
    @SCHEDULE date null,
    @BAL_QTY decimal(11, 3) null,
    @CARVED_STAMP nvarchar(50) null,
    @FROM int null,
    @TO int null
)as



delete from po_details where po_no = @po_no  and line_no = @FROM
delete from PO_HEADER where po_no = @po_no  

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
)values (
     @po_no ,
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
  values (
          @supplier_code,
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


