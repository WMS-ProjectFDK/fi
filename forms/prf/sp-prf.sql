SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[ZSP_MRP_PRF] (
    @item_no int = null,
    @prf_no varchar(20) = null
)as

declare @prf_no_old VARCHAR(30)

select top 1 @prf_no_old = h.prf_no
  from prf_details s
  inner join prf_header h on s.prf_no = h.prf_no
  where item_no = @item_no and customer_po_no in ('MRP','MRPP') and h.prf_no <> @prf_no
      and h.prf_no not in (select isnull(prf_no,'123') from po_details where item_no = @item_no)
      and APPROVAL_DATE is not null
order by PRF_DATE desc



IF (@prf_no_old is not null) BEGIN
    declare @max_line_no int

    select @max_line_no = max(line_no) from PRF_DETAILS where PRF_NO = @prf_no_old
    
    update prf_details set prf_no = @prf_no_old , line_no = @max_line_no
    where prf_no = @prf_no;

    delete PRF_HEADER where PRF_NO = @prf_no_old

    update prf_details set prf_no = @prf_no 
    where prf_no =  @prf_no_old;
    
    update prf_header set prf_no = @prf_no 
    where prf_no = @prf_no_old;


END




GO
