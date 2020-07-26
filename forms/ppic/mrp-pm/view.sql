SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
 create view [dbo].[ZVW_MRP_PM_KONVERSI] as
  select 
       r.item_no, 
       r.cr_date,
       r.work_order,
       st.lower_item_no,
       it.description, 
       s.mps_date, 
       s.mps_qty,
      CAST(sum(s.mps_qty) * quantity / quantity_base as decimal(18,2)) * (1 + (failure_rate /100))   konversi,
       r.qty
	
from mps_header r
inner join ztb_mps_details s 
on r.po_no = s.po_no and s.po_line_no = r.po_line_no
inner join (  
          select * from structure s
          inner join (
          select max(level_no) level_nos, upper_item_no upper from structure
          group by upper_item_no
          )ss on s.upper_item_no = ss.upper and s.level_no = ss.level_nos

            ) st
on st.upper_item_no = r.item_no
inner join item it 
on st.lower_item_no = it.item_no
where   s.mps_date > (select getdate()) 
       and s.mps_qty > 0
group by r.item_no, 
       r.work_order,r.cr_date,
       st.lower_item_no,
       it.description, 
       s.mps_date, 
       s.mps_qty,
       quantity, 
       quantity_base,
       failure_rate,r.qty;
 
GO
