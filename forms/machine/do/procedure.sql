SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create procedure [dbo].[ZSP_INSERT_MT] (
    @slip_no varchar(20) = null,
    @person_code varchar(10) = null
)as

SET NOCOUNT ON;
SET ANSI_NULLS ON;



BEGIN TRANSACTION T1
  insert into [TRANSACTION]( operation_date,
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
                         unit_stock,
                         wo_no,
                         item_type2,
                         rack_addr
                      ) 
  select cast(getdate() as datetime),
       h.section_code,
       d.item_no,
       id.item,
       substring(id.description,0,29),
       id.stock_subject_code,
       LEFT(CONVERT(varchar, h.slip_date,112),6),
       h.slip_date,
       h.slip_type,
       h.slip_no,
       d.qty,
       id.standard_price,
       round(isnull(id.standard_price,0) * isnull(d.qty,0),6) ,
       isnull(id.curr_code,1),
       id.standard_price,
       round(isnull(id.standard_price,0) * isnull(d.qty,0),6) ,
       h.company_code,
       d.cost_process_code,
       id.cost_subject_code,
       id.uom_q, 
       d.wo_no,
       d.item_type2, 
       d.rack_addr
  from mte_header  h 
     inner join mte_details d
     on h.slip_no = d.slip_no
     inner join item id 
     on d.item_no = id.item_no  
  where h.slip_no= @SLIP_NO AND h.approval_date is null AND h.approval_person_code is null

declare @table table(
    id int IDENTITY(1,1),
    qty bigint,
    item_no int,
    slip_date date,
    table_position int
)

insert into @table
SELECT d.qty, d.item_no,h.slip_date, (select sliptype.table_position from sliptype where sliptype.slip_type = h.slip_type) 
FROM mte_header h
inner join mte_details d 
on h.slip_no = d.slip_no where approval_date is null and h.slip_no = @slip_no 

declare @start int,
        @end INT

select @end = max(ID)
from @table
set @start = 1

declare     @id int,
    @qty bigint,
    @item_no int,
    @slip_date date,
    @table_position int

while @start <= @end BEGIN
    select @qty = qty,  
           @item_no = item_no,
           @slip_date = slip_date,
           @table_position = table_position
    from @table
    where id = @start

    exec whinventory_set @item_no,@slip_date, @qty, @table_position,0,100
    set @start = @start + 1
end

  --Update Approval Coda and Approval Person
  update mte_header set 
  approval_date = getdate(),
  approval_person_code = @PERSON_CODE 
  where slip_no = @SLIP_NO;

COMMIT TRAN T1




-- CREATE OR REPLACE PROCEDURE "PORDER"."ZSP_INSERT_MT" (
--     V_SLIP_NO IN VARCHAR,
--     V_PERSON_CODE IN VARCHAR
-- )
-- IS

-- V_Slip_Type number := 0;
-- v_receive1 number:=0;
-- v_other_receive1 number:=0;
-- v_issue1 number:=0;
-- v_receive1 number:=0;
-- v_receive1 number:=0;
-- v_ret_no number:=0;
-- v_sec_no number:=100;
-- v_issue_to_code varchar(20):='100001';
-- v_oke number := 0;

-- c_Quantity varchar(100); 
-- c_item_no varchar(100); 
-- c_slip_Date varchar(100); 
-- c_slip_type varchar(100);

-- --Cursor Untuk Iterasi Detail
-- CURSOR c_mte is 
-- SELECT d.qty, d.item_no,h.slip_date, (select sliptype.table_position from sliptype where sliptype.slip_type = h.slip_type) 
-- --case h.slip_type when '20' then 1 when '05' then 2 when '21' then 3 when '25' then 4 end   
-- FROM mte_header h
-- inner join mte_details d 
-- on h.slip_no = d.slip_no where approval_date is null and h.slip_no = v_slip_no  ; 

-- Begin
-- --cek isi material dengan inventory
-- select COUNT(1) into v_oke
-- from ( select this_inventory
-- from mte_details md
-- inner join whinventory w
-- on md.item_no = w.item_no
-- where slip_no = V_SLIP_NO and substr(V_SLIP_NO, 0, 3) != 'RMT'
-- group by md.item_no,this_inventory
-- having  this_inventory < sum(md.qty)
-- )aa;

-- IF v_oke <> 1 Then 
--   ----Insert Transaction
--   insert into transaction( operation_date,
--                          section_code,
--                          item_no,
--                          item_name,
--                          item_description,
--                          stock_subject_code,
--                          accounting_month,
--                          slip_date,
--                          slip_type,
--                          slip_no,
--                          slip_quantity,
--                          slip_price,
--                          slip_amount,
--                          curr_code,
--                          standard_price,
--                          standard_amount,
--                          company_code,
--                          cost_process_code,
--                          cost_subject_code,
--                          unit_stock,
--                          wo_no,
--                          item_type2,
--                          rack_addr
--                       ) 
--   select sysdate,
--        h.section_code,
--        d.item_no,
--        id.item,
--        substr(id.description,0,29),
--        id.stock_subject_code,
--        to_char(h.slip_date,'yyyymm'),
--        h.slip_date,
--        h.slip_type,
--        h.slip_no,
--        d.qty,
--        id.standard_price,
--        round(nvl(id.standard_price,0) * nvl(d.qty,0),6) ,
--        nvl(id.curr_code,1),
--        id.standard_price,
--        round(nvl(id.standard_price,0) * nvl(d.qty,0),6) ,
--        h.company_code,
--        d.cost_process_code,
--        id.cost_subject_code,
--        id.uom_q, 
--        d.wo_no,
--        d.item_type2, 
--        d.rack_addr
--   from mte_header  h 
--      inner join mte_details d
--      on h.slip_no = d.slip_no
--      inner join item id 
--      on d.item_no = id.item_no  
--   where h.slip_no= V_SLIP_NO AND h.approval_date is null AND h.approval_person_code is null;
--   commit;

--   --Insert WH Inventory
--   OPEN c_mte; 
--     LOOP 
--     FETCH c_mte into c_Quantity,c_item_no,c_slip_Date,c_slip_type; 
--       EXIT WHEN c_mte%notfound; 
--       begin
--       whinventory_set(c_item_no,c_slip_date, c_quantity, c_slip_type,v_ret_no,v_sec_no);
--       pcinventory_set(c_item_no,c_slip_date, c_quantity, c_slip_type,v_ret_no,v_sec_no,v_issue_to_code);
      
--       --INSERT ZSP_WH_ITEM_FIFO USER=2, NAME: UENG, DATE: 30-07-2017
--       ZSP_WH_ITEM_FIFO (c_item_no);
--       commit;
--       end;
--   END LOOP; 
--   CLOSE c_mte;  

--   --Update Approval Coda and Approval Person
--   update mte_header set 
--   approval_date = sysdate,
--   approval_person_code = V_PERSON_CODE 
--   where slip_no = V_SLIP_NO;
--   commit;

-- else
--   insert into ZTB_MESSAGE (type_pesan,isi_pesan, operation_date) values ('MT',v_slip_no, sysdate);
--   commit; 
-- end if;

-- END ;
 
GO
