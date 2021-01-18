SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[ZSP_GR_DELETE] (
    @gr_no varchar(20) = null
)as

BEGIN TRANSACTION delegeGR
 
DECLARE @table TABLE (
    idx int identity(1,1),
    po_no nvarchar(20),
    po_line_no int,
    gr_qty bigint,
    item_no int,
    gr_date date
)

insert into @table 
SELECT s.po_no,
       s.po_line_no,
       s.qty,
       s.item_no,
       r.gr_date
from gr_details s
inner join gr_header r
on s.gr_no = r.gr_no
where  s.gr_no = @GR_NO 

declare @thismonth nvarchar(6) = (SELECT CONVERT(nvarchar(6), GETDATE(), 112))
declare @lastmonth nvarchar(6) = (SELECT CONVERT(nvarchar(6), dateadd(m,-1,GETDATE()), 112))


DECLARE @counter int

SET @counter = 1


WHILE(@counter <= (select MAX(idx) FROM @table))
BEGIN
    DECLARE @t_po_no nvarchar(20)
    DECLARE @t_po_line_no int 
    DECLARE @t_gr_qty bigint
    DECLARE @t_item_no INT
    DECLARE @t_gr_date DATE

    SELECT distinct @t_po_no = po_no,
                    @t_po_line_no = po_line_no,
                    @t_gr_qty = gr_qty, 
                    @t_item_no = item_no,
                    @t_gr_date = gr_date
    FROM @table WHERE idx = @counter
    
    declare @formatSlipdate nvarchar(6) = (SELECT CONVERT(nvarchar(6), @t_gr_date, 112))
    


    update po_details set bal_qty = bal_qty + @t_gr_qty,
                                gr_qty = gr_qty - @t_gr_qty
    where po_no = @t_po_no and line_no = @t_po_Line_no and bal_qty >= 0 ;
  

     IF @formatSlipdate = @thismonth  BEGIN
        update whinventory set this_inventory = this_inventory - @t_gr_qty , 
                                    receive1 = receive1  - @t_gr_qty  
        where item_no = @t_item_no;
     END ELSE IF @formatSlipdate = @lastmonth BEGIN
         update whinventory set this_inventory = this_inventory - @t_gr_qty , 
                                     receive2 = receive2  - @t_gr_qty , 
                                     last_inventory = last_inventory - @t_gr_qty 
         where item_no = @t_item_no;
    END 

    SET @counter = @counter + 1
END 

--DELETE TRANSACTION
delete from [transaction] where slip_no = @GR_NO
--DELETE ACCOUNT_PAYABLE
delete from account_payable where gr_no = @GR_NO and type=1 ;
--DELETE GR_HEADER
delete from gr_header where gr_no = @GR_NO;
--DELETE GR_DETAILS
delete from gr_details where gr_no = @GR_NO;

commit TRANSACTION deleteGr

GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
 CREATE procedure [dbo].[whinventory_set](
    @item_no integer = null,
    @slip_date date = null,
    @slip_qty bigint = null,
    @table_position int =null,
    @ret_no int = null out,
    @sec_code int = null 
 )as

   declare @receive1             bigint  =0
   declare @other_receive1       bigint  =0
   declare @issue1               bigint  =0
   declare @other_issue1         bigint  =0
   declare @stocktaking_adjust1  bigint  =0
   declare @this_inventory       bigint  =0
   declare @receive2             bigint  =0
   declare @other_receive2       bigint  =0
   declare @issue2               bigint  =0
   declare @other_issue2         bigint  =0
   declare @stocktaking_adjust2  bigint  =0
   declare @last_inventory       bigint  =0
   declare @thismonth nvarchar(6) = (SELECT CONVERT(nvarchar(6), GETDATE(), 112))
   declare @lastmonth nvarchar(6) = (SELECT CONVERT(nvarchar(6), dateadd(m,-1,GETDATE()), 112))
   declare @formatSlipdate nvarchar(6) = (SELECT CONVERT(nvarchar(6), @slip_date, 112))

If @thismonth = @formatSlipdate BEGIN                    --???
               If    @table_position = 1 BEGIN  
                    set @receive1  = @slip_qty 
                    set @this_inventory =  @slip_qty
               END ELSE IF @table_position = 2 BEGIN  
                    set @other_receive1   = @slip_qty 
                    set @this_inventory   =  @slip_qty 
               END ELSE IF @table_position = 3 BEGIN  
                    set @issue1  = @slip_qty 
                    set @this_inventory = -@slip_qty 
               END ELSE IF @table_position = 4 BEGIN  
                    set @other_issue1  = @slip_qty 
                    set @this_inventory = -@slip_qty 
               End 
END ELSE IF @lastmonth = @formatSlipdate BEGIN
               
                If    @table_position = 1 BEGIN  
                    set @receive2  = @slip_qty 
                    set @this_inventory =  @slip_qty
                    set @last_inventory =  @slip_qty
               END ELSE IF @table_position = 2 BEGIN  
                    set @other_receive2   = @slip_qty 
                    set @this_inventory   =  @slip_qty 
                    set @last_inventory =  @slip_qty
               END ELSE IF @table_position = 3 BEGIN  
                    set @issue2  = @slip_qty 
                    set @this_inventory = -@slip_qty 
                    set @last_inventory = -@slip_qty
               END ELSE IF @table_position = 4 BEGIN  
                    set @other_issue2  = @slip_qty 
                    set @this_inventory = -@slip_qty 
                    set @last_inventory =  -@slip_qty
               End 
End 


 IF NOT EXISTS(select * from whinventory where item_no = @item_no and section_code = @sec_code) BEGIN
    insert into whinventory(
               operation_date,
               section_code,
               item_no,
               location,
               this_month,
               receive1,
               other_receive1,
               issue1,
               other_issue1,
               stocktaking_adjust1,
               this_inventory,
               last_month,
               receive2,
               other_receive2,
               issue2,
               other_issue2,
               stocktaking_adjust2,
               last_inventory,
               last2_inventory
            ) VALUES (
               getdate(),
               @sec_code,
               @item_no,
               null,
               @thismonth,
               isnull(@receive1,0),
               isnull(@other_receive1,0),
               isnull(@issue1,0),
               isnull(@other_issue1,0),
               isnull(@stocktaking_adjust1,0),
               isnull(@this_inventory,0),
               @lastmonth,
               isnull(@receive2,0),
               isnull(@other_receive2,0),
               isnull(@issue2,0),
               isnull(@other_issue2,0),
               isnull(@stocktaking_adjust2,0),
               isnull(@last_inventory,0),
               0
            ) 
 END ELSE BEGIN
         update whinventory set
               operation_date        = getdate(),
               receive1              = receive1            + isnull(@receive1           ,0),
               other_receive1        = other_receive1      + isnull(@other_receive1     ,0),
               issue1                = issue1              + isnull(@issue1             ,0),
               other_issue1          = other_issue1        + isnull(@other_issue1       ,0),
               stocktaking_adjust1   = stocktaking_adjust1 + isnull(@stocktaking_adjust1,0),
               this_inventory        = this_inventory      + isnull(@this_inventory     ,0),
               receive2              = receive2            + isnull(@receive2           ,0),
               other_receive2        = other_receive2      + isnull(@other_receive2     ,0),
               issue2                = issue2              + isnull(@issue2             ,0),
               other_issue2          = other_issue2        + isnull(@other_issue2       ,0),
               stocktaking_adjust2   = stocktaking_adjust2 + isnull(@stocktaking_adjust2,0),
               last_inventory        = last_inventory      + isnull(@last_inventory     ,0)
            where item_no = @item_no
              and section_code = @sec_code

           If    @table_position = 1 BEGIN              
               update item  set  receive_date = @slip_date where item_no = @item_no and (receive_date < @slip_date or receive_date is null);
          END ELSE IF @table_position = 2 BEGIN              
               update item  set  receive_date = @slip_date where item_no = @item_no and (receive_date < @slip_date or receive_date is null);
          END ELSE IF @table_position = 3 BEGIN             
               update item  set  issue_date   = @slip_date where item_no = @item_no and (issue_date < @slip_date or issue_date is null);
          END ELSE IF @table_position = 4 BEGIN              
               update item  set  issue_date   = @slip_date where item_no = @item_no and (issue_date < @slip_date or issue_date is null); 
          End 
 END

GO


SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE procedure [dbo].[ZSP_INSERT_GR] (
    @user VARCHAR(100)
)as


-- INSERT AP
insert into account_payable (
customer_code, 
bl_no,         
type,                  
payment_date,  
amt,                   
bl_date,       
curr_code,     
rate,          
amt_f,         
reg_date,      
upto_date,     
pdays,         
pdesc,         
gr_no)   
select distinct ztb_gr_temp.supplier_code, 
       gr_no,
       '1',
       gr_date, 
       amt_l,
       due_date,
       curr_code, 
       ex_rate,
       amt_o,
       (select getdate()),
       (select getdate()),
       pdays,
       pdesc,
       gr_no
from ztb_gr_temp 	where gr_sts = 'HEADER' and person_code = @user; --and gr_no not in (select gr_no from gr_header);

insert into gr_header (
gr_no,  		
gr_date,  		
inv_date,  	
supplier_code,
inv_no,  		
curr_code,  
ex_rate,  		
pdays,  		
pdesc,  		
due_date,  	
remark,  		
amt_o,  		
amt_l,  		
person_code,
slip_type
) 

select distinct gr_no, 
       gr_date,
       gr_date,
       supplier_code,
       gr_no,
       curr_code,
       ex_rate,
       pdays,
       pdesc,
       due_date,
       remark,
       amt_o,
       amt_l,
       person_code, 
       slip_type      
from ztb_gr_temp 	where gr_sts = 'HEADER' and person_code = @user; --and gr_no not in (select gr_no from gr_header);

insert into [transaction]( operation_date,
                         section_code,
                         item_no,
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
                         cost_process_code,
                         cost_subject_code,
                         suppliers_price,
                         order_number,
                         line_no,
                         purchase_quantity,
                         purchase_price,
                         purchase_amount,
                         purchase_unit,
                         unit_stock
                      ) 
      
select distinct 
        (select getdate()),
        100,
        cast(ztb_gr_temp.item_no as int),
        ztb_gr_temp.item_name,
        SUBSTRING(item.description,0,30) as description,
        item.stock_subject_code,
        (SELECT CONVERT(nvarchar(6),ztb_gr_temp.gr_Date, 112)),
        gr_date, 
        ztb_gr_temp.slip_type, 
        gr_no,
        ztb_gr_temp.gr_qty,
        ztb_gr_temp.gr_u_price,
        ztb_gr_temp.amt_l,
        ztb_gr_temp.curr_code,
        item.standard_price,
        ztb_gr_temp.gr_qty * item.standard_price,
        ztb_gr_temp.supplier_code,
        ztb_gr_temp.cost_process_code,
        ztb_gr_temp.cost_subject_code,
        ztb_gr_temp.suppliers_price,
        ztb_gr_temp.po_no,
        ztb_gr_temp.po_line_no,
        ztb_gr_temp.gr_qty,
        ztb_gr_temp.gr_u_price,
        ztb_gr_temp.amt_l,
        ztb_gr_temp.gr_uom_q,
        ztb_gr_temp.gr_uom_q
from  ztb_gr_temp 
inner join item
on item.item_no = ztb_gr_temp.item_no
inner join po_details s
on ztb_gr_temp.po_no = s.po_no and ztb_gr_temp.po_line_no = s.line_no
where gr_sts = 'DETAILS' and person_code = @user; 



DECLARE @table TABLE (
    idx int identity(1,1),
    po_no nvarchar(20),
    po_line_no int,
    gr_qty bigint,
    item_no int,
    gr_date date,
    tablePost int 
)

insert into @table
 SELECT distinct po_no,po_line_no,gr_qty, item_no,gr_date, (select table_position from sliptype where sliptype.slip_type = ztb_gr_temp.slip_type) tablePost 
  FROM ztb_gr_temp
  where  gr_sts = 'DETAILS' and person_code = @user

DECLARE @counter int

SET @counter = 1

select MAX(idx) FROM @table

WHILE(@counter <= (select MAX(idx) FROM @table))
BEGIN
    DECLARE @t_po_no nvarchar(20)
    DECLARE @t_po_line_no int 
    DECLARE @t_gr_qty bigint
    DECLARE @t_item_no INT
    DECLARE @t_gr_date DATE
    DECLARE @t_tablepost INT

    SELECT distinct @t_po_no = po_no,
                    @t_po_line_no = po_line_no,
                    @t_gr_qty = gr_qty, 
                    @t_item_no = item_no,
                    @t_gr_date = gr_date, 
                    @t_tablepost = tablePost 
    FROM @table WHERE idx = @counter


    update po_details set bal_qty = bal_qty - @t_gr_qty,
                                gr_qty = gr_qty + @t_gr_qty
    where po_no = @t_po_no and line_no = @t_po_Line_no and bal_qty >= 0 ;
      
    exec whinventory_set @t_item_no,@t_gr_date, @t_gr_qty, @t_tablepost,0,100
    --pcinventory_set(c_item_no,c_slip_date, c_quantity, c_slip_type,v_ret_no,v_sec_no,v_issue_to_code);

    -- Do your work here

    SET @counter = @counter + 1
END


-- INSERT GR_DETAILS
insert into gr_details (
gr_no,	
line_no,	
item_no,	
origin_code,
po_no,		
po_line_no,
qty,		
uom_q,		
u_price,	
amt_o,		
amt_l,		
loc1,		
loc_qty1,
upto_date,
reg_date	
)
select distinct gr_no, 
       ztb_gr_temp.line_no, 
       ztb_gr_temp.item_no, 
       ztb_gr_temp.origin_code,
       ztb_gr_temp.po_no, 
       ztb_gr_temp.po_line_no,
       ztb_gr_temp.gr_qty, 
       item.uom_q, 
       s.u_price,
       ztb_gr_temp.amt_o, 
       ztb_gr_temp.amt_l, 
       '88475', 
       ztb_gr_temp.gr_qty,
       (select getdate()),
       (select getdate())
from ztb_gr_temp 	
inner join item
on item.item_no = ztb_gr_temp.item_no
inner join po_details s
on ztb_gr_temp.po_no = s.po_no and ztb_gr_temp.po_line_no = s.line_no
where gr_sts = 'DETAILS' and person_code = @user; --and gr_no||line_no not in (select gr_no||line_no from gr_details);



update ztb_gr_temp set gr_sts = 'EXISTS' 
where gr_sts <> 'EXISTS' and person_code = @user;--and gr_no in (select gr_no from gr_header where gr_Date > '01-JAN-18');



GO

