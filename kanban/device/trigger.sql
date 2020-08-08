
create trigger ZTR__WH_KANBAN_TRANS
on ZTB_WH_KANBAN_TRANS 
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
        