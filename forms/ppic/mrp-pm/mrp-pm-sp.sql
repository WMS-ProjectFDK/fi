SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--exec zsp_mrp_pm

-- select * from ztb_mps_details where po_no = 'FI/20-163'

--select * from ZTB_MRP_DATA_PCK where item_no = '2225326'

-- select 
--        distinct
--        case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end lower_item_no,
--        it.description
-- select *       
-- from mps_header r
-- inner join ztb_mps_details s 
-- on r.po_no = s.po_no and s.po_line_no = r.po_line_no
-- inner join (  
--             select * from structure s
--             inner join (
--                           select max(level_no)level_no_max, upper_item_no as UPPER_ITEM_NOS from STRUCTURE 
--                           group by UPPER_ITEM_NO 
--               )ss on s.UPPER_ITEM_NO = ss.UPPER_ITEM_NOS and s.LEVEL_NO = ss.level_no_max
--               where LOWER_ITEM_NO = 2225326
--             ) st
-- on st.UPPER_ITEM_NO = r.ITEM_NO 
-- -- inner join item it 
-- -- on case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end = it.item_no
-- where   st.LOWER_ITEM_NO = 2225326
        
--         -- and upper_item_no = '88680'
--   group by mps_date,case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end,quantity_base,quantity,failure_rate
  

--  select case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end lower_item_no,
--         mps_date,
--         CAST(sum(s.mps_qty) * quantity / quantity_base as decimal(18,2)) * (1 + (failure_rate /100))   qty_p,
--         1
--    select *     
--   from mps_header r
--   inner join ZTB_MPS_DETAILS s 
--   on r.po_no = s.po_no and s.po_line_no = r.po_line_no
--   inner join (  
--             select * from structure s
--               ) st
--   on cast(st.upper_item_no as   varchar(10))+cast(level_no  as varchar(10)) = cast(r.item_no  as varchar(10))+cast(s.bom_level as  varchar(10))
--   inner join item it 
--   on case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end = it.item_no
--   where  mps_date > cast(getdate() as date) and datediff(d,getdate(),mps_date) <=90
        
--         -- and upper_item_no = '88680'
--   group by mps_date,case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end,quantity_base,quantity,failure_rate
  

--1232160	2020-07-27	5000000.000	5

-- update ztb_mrp_data_pck set N_1 = bb.jum from ztb_mrp_data_pck aa inner join (
--     select sum(qty_p) as jum,lower_item_no as item_no from tableplan_used                        
-- where  mps_date  = '2020-07-27' and flag = 5 group by lower_item_no )
-- bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 5

--select * from tableplan_used where  lower_item_no = '1232160' and mps_date = cast(getdate() as date) order by mps_date 
 



--    )bb on bb.ITEM_NO = aa.ITEM_NO where ztb_mrp_data_pck.no_id = 3

-- select * from ztb_mrp_data_pck order by item_no,no_id

--select * from ztb_mrp_data_pck where item_no in (select lower_item_no from tableplan_used)

CREATE PROCEDURE [dbo].[zsp_mrp_pm_item] (
    @item_no int = null
) AS

declare @table table(
   ID  int identity(1,1),
   lower_item_no int,
   desk NVARCHAR(100)
)

declare @table_produksi table (
    id INT IDENTITY(1,1),
    po_no varchar(30),
    po_line_no int,
    qty decimal(14,6)
)

declare @table_m_details table(
    ID INT IDENTITY(1,1),
    po_no varchar(30),
    po_line_no int,
    mps_date date,
    mps_qty decimal(14,6)
)

--##################################CACL MPS START #####################################

delete from ZTB_MPS_DETAILS 
where ITEM_NO in (select upper_item_no from STRUCTURE where LOWER_ITEM_NO = @item_no)

insert into @table_produksi
select distinct r.po_no, r.po_line_no,  r.qty - isnull(qty_prod,isnull(cc.production,0)) - isnull(sum(s.mps_qty),0) Qty
from mps_header r
left outer join (select * from mps_details where mps_date > cast(getdate() + 90 as date)) s
on r.po_line_no = s.po_line_no and r.po_no = s.po_no
left outer join (select isnull(sum(case when slip_type = 87 then slip_quantity *-1 else SLIP_QUANTITY end ),0) Production, wo_no from production_income group by wo_no) cc
on r.work_order = cc.wo_no
left outer join (select wo_no,isnull(sum(qty_prod),0) qty_prod from 
                    (select distinct wo_no,plt_no,qty_prod,qty_order from ztb_m_plan where upload = 1 )aa group by wo_no)dd
on r.work_order = dd.wo_no
where r.status is not null --and r.work_order = '0010194-LR03C1-1'--and r.po_no = '0010194'--and r.po_no = '18FI075' and r.po_line_no = 1
      and r.po_no+ r.po_line_no not in (
        select distinct r.po_no+ r.po_line_no 
        from mps_header r
        left outer join mps_details s
        on r.po_no = s.po_no and r.po_line_no = s.po_line_no
        where s.po_no is null
      )
      and r.ITEM_NO in (select upper_item_no from STRUCTURE where LOWER_ITEM_NO = @item_no)
group by r.po_no, r.po_line_no,production,r.qty,qty_prod
having r.qty - isnull(cc.production,0) - isnull(sum(s.mps_qty),0) > 0;

declare @start int = 1
declare @end int

declare @start_x int = 1
declare @end_x int

select @end = isnull(max(ID),0)
from @table_produksi
 

declare @po_no VARCHAR(10)
declare @po_line_no INT
declare @qty decimal(16,4)  

declare @po_no_x VARCHAR(10)
declare @po_line_no_x INT
declare @mps_qty decimal(16,4)  
declare @mps_date date 

 insert into ZTB_MPS_DETAILS(PO_NO,PO_LINE_NO,MPS_QTY,MPS_DATE,UPLOAD_DATE)
 select PO_no,po_line_no,mps_qty,mps_date,cast(getdate() as date)
 from mps_details
 where mps_date between cast(getdate() as date) and cast(getdate()+90 as date)  
 and po_no + cast(po_line_no as varchar(10)) not in ( 
    select po_no + cast(po_line_no as varchar(10))
    from @table_produksi
 )



    
while @start <= @end BEGIN
    
    select @po_no = po_no,
           @po_line_no = po_line_no,
           @qty = qty
    from @table_produksi
    where id = @start

     insert into @table_m_details
     select po_no, po_line_no, mps_date, mps_qty from mps_details
     where mps_date between cast(getdate() as date) and cast(getdate()+90 as date)  
     and po_no = @po_no and PO_LINE_NO = @po_line_no
     order by mps_date;


    select @end_x = isnull(max(ID),0) 
    from @table_m_details
    
    while @start_x <= @end_x
    BEGIN
        select @po_no_x = po_no,
               @po_line_no_x = po_line_no,
               @mps_date = mps_date,
               @mps_qty = mps_qty
        from @table_m_details
        where id = @start_x

        IF  @qty > @mps_qty  BEGIN
            insert into ZTB_MPS_DETAILS(PO_NO,PO_LINE_NO,MPS_QTY,MPS_DATE,UPLOAD_DATE)
            select @PO_no,@po_line_no,@mps_qty,@mps_date,cast(getdate() as date)
            set @qty = @qty - @mps_qty
        END ELSE BEGIN
            insert into ZTB_MPS_DETAILS(PO_NO,PO_LINE_NO,MPS_QTY,MPS_DATE,UPLOAD_DATE)
            select @PO_no,@po_line_no,@qty,@mps_date,cast(getdate() as date)
            set @qty = 0
            set @start_x = @end_x
        END 


        set @start_x = @start_x + 1
    END
    
    delete from @table_m_details
    set @start = @start + 1
end 


--##################################CACL MPS END #####################################








insert into @table
select 
       distinct
       case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end lower_item_no,
       it.description
from mps_header r
inner join mps_details s 
on r.po_no = s.po_no and s.po_line_no = r.po_line_no
inner join (  
            select * from structure s
            inner join (
                          select max(level_no)level_no_max, upper_item_no as UPPER_ITEM_NOS from STRUCTURE 
                          group by UPPER_ITEM_NO 
              )ss on s.UPPER_ITEM_NO = ss.UPPER_ITEM_NOS and s.LEVEL_NO = ss.level_no_max
            ) st
on st.UPPER_ITEM_NO = r.ITEM_NO 
inner join item it 
on case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end = it.item_no
where   s.mps_date > cast(getdate() as date) 
        and case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end = @item_no







-- create table tableplan_used (
--    ID  int identity(1,1),
--    lower_item_no int,
--    mps_date date,
--    qty_p bigint,
--    flag int
-- )
 
delete from tableplan_used where lower_item_no = @item_no

--inventory 4
insert into tableplan_used 
select bb.item_no,getdate(),isnull(bb.this_inventory,0) - isnull(cc.qty_not_approve,0)   ,4
            from whinventory bb
            inner join ((select distinct lower_item_no as item_no,desk
                        from @table))aa
            on aa.item_no = bb.ITEM_NO
            left outer join (   select item_no,sum(s.qty) qty_not_approve from mte_header r
                                        inner join mte_details s on r.slip_no = s.slip_no
                                        where r.approval_Date is null and slip_date > '01-JAN-19'
                                        group by item_no)cc
                    on  bb.item_no = cc.item_no

--purchase Plan 1
insert into tableplan_used 
select  aa.item_no,  
        require_date as DateDiff, 
        sum(isnull(case when remainder_qty < 0 then 0 else remainder_qty end,0)) JumPre,
        3
from prf_details aa
inner join (select distinct lower_item_no as item_no
            from @table) bb  
on aa.item_no = bb.item_no
where aa.require_date > cast(getdate() as date) and datediff(d, cast(getdate() as date),require_date) <= 90
and aa.item_no not in ('2211524',
                              '2212349',
                              '2211525',
                              '2212350',
                              '2211521',
                              '2212353',
                              '2211482',
                              '2212354',
                              '2211523',
                              '2212356',
                              '2211487',
                              '2211485',
                              '2212351',
                              '2211486',
                              '2212352',
                              '2111425',
                              '2112268',
                              '2111426',
                              '2112269',
                              '2111423',
                              '2112272',
                              '2111387',
                              '2112273',
                              '2111388',
                              '2112274',
                              '2111391',
                              '2223131',
                              '2123131'
                              )
group by aa.item_no,require_date
order by aa.item_no,require_date


--purchase plan 5

insert into tableplan_used
select  bb.item_no,  
        aa.di_date as DateDiff, 
        sum(qty) JumPre ,
        5
from di_header aa
inner join di_details bb
on aa.di_no = bb.di_no
where item_no in ('2211524',
                              '2212349',
                              '2211525',
                              '2212350',
                              '2211521',
                              '2212353',
                              '2211482',
                              '2212354',
                              '2211523',
                              '2212356',
                              '2211487',
                              '2211485',
                              '2212351',
                              '2211486',
                              '2212352',
                              '2111425',
                              '2112268',
                              '2111426',
                              '2112269',
                              '2111423',
                              '2112272',
                              '2111387',
                              '2112273',
                              '2111388',
                              '2112274',
                              '2111391',
                              '2223131',
                              '2123131'
                              )
and datediff(d,cast(getdate() as date),aa.di_date) > 0                               
group by bb.item_no,di_Date
order by bb.item_no,di_Date;


--purchase plan 2 -- 5

insert into tableplan_used
select  aa.item_no,  
        require_date as DateDiff, 
        sum(isnull(case when remainder_qty < 0 then 0 else remainder_qty end,0)) JumPre,
        5
from prf_details aa
inner join (select distinct item_no
            from ztb_mrp_data_pck) bb  
on aa.item_no = bb.item_no
where aa.require_date > cast(getdate() as date)
      and aa.remainder_qty <> 0 or remainder_qty is null
      and datediff(d, cast(getdate() as date),require_date ) between 0 and 90 
      and aa.item_no not in ('2211524',
                              '2212349',
                              '2211525',
                              '2212350',
                              '2211521',
                              '2212353',
                              '2211482',
                              '2212354',
                              '2211523',
                              '2212356',
                              '2211487',
                              '2211485',
                              '2212351',
                              '2211486',
                              '2212352',
                              '2111425',
                              '2112268',
                              '2111426',
                              '2112269',
                              '2111423',
                              '2112272',
                              '2111387',
                              '2112273',
                              '2111388',
                              '2112274',
                              '2111391',
                              '2223131',
                              '2123131'
                              )
group by aa.item_no,require_date
order by aa.item_no,require_date;



--arrive Plan--2
insert into tableplan_used 
select  aa.item_no,  
        eta  as DateDiff, 
        sum(bal_qty) JumPre,
        2
from po_details aa
inner join (select distinct lower_item_no as item_no
            from @table) bb  
on aa.item_no = bb.item_no
where aa.eta > cast(getdate() as date) and  datediff(d, cast(getdate() as date),eta )  <= 90
      and aa.item_no not in ('2211524',
                              '2212349',
                              '2211525',
                              '2212350',
                              '2211521',
                              '2212353',
                              '2211482',
                              '2212354',
                              '2211523',
                              '2212356',
                              '2211487',
                              '2211485',
                              '2212351',
                              '2211486',
                              '2212352',
                              '2111425',
                              '2112268',
                              '2111426',
                              '2112269',
                              '2111423',
                              '2112272',
                              '2111387',
                              '2112273',
                              '2111388',
                              '2112274',
                              '2111391',
                              '2223131',
                              '2123131'
                              )
group by aa.item_no,eta
order by aa.item_no,eta;



-- IF EXISTS(select * from tableplan_used) BEGIN
--     
-- END


 insert into tableplan_used
 select case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end lower_item_no,
        mps_date,
        CAST(sum(s.mps_qty) * quantity / quantity_base as decimal(18,2)) * (1 + (failure_rate /100))   qty_p,
        1
  from mps_header r
  inner join ZTB_MPS_DETAILS s 
  on r.po_no = s.po_no and s.po_line_no = r.po_line_no
  inner join (  
            select * from structure s
            inner join (
                          select max(level_no)level_no_max, upper_item_no as UPPER_ITEM_NOS from STRUCTURE 
                          group by UPPER_ITEM_NO 
              )ss on s.UPPER_ITEM_NO = ss.UPPER_ITEM_NOS and s.LEVEL_NO = ss.level_no_max
            ) st
  on st.UPPER_ITEM_NO = r.ITEM_NO 
  inner join item it 
  on case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end = it.item_no
  where  mps_date > cast(getdate() as date) and datediff(d,getdate(),mps_date) <=90
        
        -- and upper_item_no = '88680'
  group by mps_date,case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end,quantity_base,quantity,failure_rate
  
insert into ZTB_MRP_DATA_pck_DELETE
select getdate(),* from ZTB_MRP_DATA_pck
 
 delete from ztb_mrp_data_pck
    

 insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
 select '1','PLAN',lower_item_no,desk
 from @table 
          
          insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
          select '4','INVENTORY = '+  cast(isnull(bb.this_inventory,0) - isnull(cc.qty_not_approve,0) as varchar(30))  +'',bb.item_no,aa.desk
            from whinventory bb
            inner join ((select distinct lower_item_no as item_no,desk
                        from @table))aa
            on aa.item_no = bb.ITEM_NO
            left outer join (   select item_no,sum(s.qty) qty_not_approve from mte_header r
                                        inner join mte_details s on r.slip_no = s.slip_no
                                        where r.approval_Date is null and slip_date > '01-JAN-19'
                                        group by item_no)cc
                    on  bb.item_no = cc.item_no
          
          insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
          select '2','ARRIVE',lower_item_no,desk
          from @table 
          
          
          insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
          select '3','PURCHASE',lower_item_no,desk
          from @table
          
          
   
          
          
        --   insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
        --   select '5','PURCHASE2 ',lower_item_no,desk
        --   from @table 




DECLARE @date date = getdate()

  
    declare @qry nvarchar(max)
    set @start = 1
    set @end = 90
    declare @dateStart date 
    set @dateStart = dateadd(d,1,@date)
    
    
    while @start <= @end BEGIN
        
            set @date = dateadd(d,1,@date)  
            declare @tanggal NVARCHAR(10)
                
            set @qry = 'update ztb_mrp_data_pck set N_' + cast(@start as varchar(2)) + ' = bb.jum from ztb_mrp_data_pck aa inner join (select sum(qty_p) as jum,lower_item_no as item_no from tableplan_used
                        where  mps_date  = '''+ cast(@date as nvarchar(10)) +''' and flag = 1 group by lower_item_no )bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 1'
         
            exec (@qry)
            
            set @qry = ''

            set @qry = 'update ztb_mrp_data_pck set N_' + cast(@start as varchar(2)) + ' = bb.jum from ztb_mrp_data_pck aa inner join (select sum(qty_p) as jum,lower_item_no as item_no from tableplan_used
                        where  mps_date  = '''+ cast(@date as nvarchar(10)) +''' and flag = 3 group by lower_item_no )bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 3'
         
            exec (@qry)
            
            set @qry = ''

            set @qry = 'update ztb_mrp_data_pck set N_' + cast(@start as varchar(2)) + ' = bb.jum from ztb_mrp_data_pck aa inner join (select sum(qty_p) as jum,lower_item_no as item_no from tableplan_used
                        where  mps_date  = '''+ cast(@date as nvarchar(10)) +''' and flag = 2 group by lower_item_no )bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 2'
           
            exec (@qry)
            
            -- set @qry = ''

            -- set @qry = 'update ztb_mrp_data_pck set N_' + cast(@start as varchar(2)) + ' = bb.jum from ztb_mrp_data_pck aa inner join (select sum(qty_p) as jum,lower_item_no as item_no from tableplan_used
            --             where  mps_date  = '''+ cast(@date as nvarchar(10)) +''' and flag = 5 group by lower_item_no )bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 5'
           
            -- exec (@qry)
            
            set @qry = ''  
            if(@start = 1) BEGIN
                    set @qry = 'update ztb_mrp_data_pck set N_' + cast(@start as varchar(2)) + ' = bb.jum from ztb_mrp_data_pck aa 
                            inner join (select bb.ITEM_NO,bb.THIS_INVENTORY - sum(case when no_id = 1 then N_1 * -1 else  N_1 end  ) as jum
                                        from ztb_mrp_data_pck aa
                                        inner join WHINVENTORY bb
                                        on aa.ITEM_NO = bb.ITEM_NO
                                        where NO_ID in (1,2)
                                              and bb.ITEM_no in (select lower_item_no from tableplan_used)
                                    group by bb.ITEM_NO,bb.THIS_INVENTORY  

                                )bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 4'
            
       exec (@qry)
      end ELSE BEGIN
                set @qry = 'update ztb_mrp_data_pck set N_' + cast(@start as varchar(2)) + ' = cc.jum from ztb_mrp_data_pck aa                     
                inner join (                       
                    select aa.item_no,juma + jumb as jum                                
                    from (                                
                        select item_no,sum(case when no_id = 1 then N_' + cast(@start as varchar(2)) + ' * -1 else  N_' + cast(@start as varchar(2)) + ' end  ) as jumb                                
                        from ztb_mrp_data_pck aa                                
                        where NO_ID in (1,2)                                
                        group by ITEM_NO)aa                                
                    inner join (                                
                        select item_no,sum(case when no_id = 1 then N_' + cast(@start-1 as varchar(2)) + ' * -1 else  N_' + cast(@start-1 as varchar(2)) + ' end  ) as juma                                
                        from ztb_mrp_data_pck aa                                
                        where NO_ID in (4)                               
                        group by ITEM_NO                                
                        )bb on aa.item_no  = bb.item_no                       
                )cc on cc.ITEM_NO = aa.ITEM_NO where no_id = 4'

                
       exec (@qry)
      END 
      
       

        set @start = @start + 1
    end

--drop table tableplan_used
--rop table tableplan_used

-- CREATE OR REPLACE PROCEDURE "PORDER"."ZSP_MRP_PM" is

-- v_i number := 0;
-- v_days number:= 1;
-- v_days1 number:= 0;
-- v_tgl VARCHAR(100);
-- v_qty number;
-- c_qty number;
-- c_qty_p number;
-- c_Date date;
-- c_item VARCHAR(100);
-- c_day number;
-- c_description varchar(100);
-- v_inventory number:=0;
-- v_purchase number:=0;
-- v_preq number:=0;
-- v_inventory_awal number :=0;
-- v_str varchar(3000);
-- c_wo varchar(3000);


-- cursor c_insert is
-- select 
--        distinct
--        case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end lower_item_no,
--        it.description
-- from mps_header r
-- inner join mps_details s 
-- on r.po_no = s.po_no and s.po_line_no = r.po_line_no
-- inner join (  
--           select * from structure 

--             ) st
-- on st.upper_item_no||level_no = r.item_no||bom_level
-- inner join item it 
-- on case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end = it.item_no
-- where   s.mps_date > (select trim(sysdate) from dual) --and upper_item_no = '88680'
-- ;


-- cursor c_purchase_plan is
-- select  aa.item_no,  
--         to_Date(require_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') as DateDiff, 
--         sum(nvl(case when remainder_qty < 0 then 0 else remainder_qty end,0)) JumPre
-- from prf_details aa
-- inner join (select distinct item_no
--             from ztb_mrp_data_pck) bb  
-- on aa.item_no = bb.item_no
-- where aa.require_date > (select trim(sysdate) from dual) and to_Date(require_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') <= 90
-- and aa.item_no not in ('2211524',
--                               '2212349',
--                               '2211525',
--                               '2212350',
--                               '2211521',
--                               '2212353',
--                               '2211482',
--                               '2212354',
--                               '2211523',
--                               '2212356',
--                               '2211487',
--                               '2211485',
--                               '2212351',
--                               '2211486',
--                               '2212352',
--                               '2111425',
--                               '2112268',
--                               '2111426',
--                               '2112269',
--                               '2111423',
--                               '2112272',
--                               '2111387',
--                               '2112273',
--                               '2111388',
--                               '2112274',
--                               '2111391',
--                               '2223131',
--                               '2123131'
--                               )
-- group by aa.item_no,require_date
-- order by aa.item_no,require_date;

-- cursor c_di_plan is
-- select  bb.item_no,  
--         to_Date(aa.di_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') as DateDiff, 
--         sum(qty) JumPre from di_header aa
-- inner join di_details bb
-- on aa.di_no = bb.di_no
-- where item_no in ('2211524',
--                               '2212349',
--                               '2211525',
--                               '2212350',
--                               '2211521',
--                               '2212353',
--                               '2211482',
--                               '2212354',
--                               '2211523',
--                               '2212356',
--                               '2211487',
--                               '2211485',
--                               '2212351',
--                               '2211486',
--                               '2212352',
--                               '2111425',
--                               '2112268',
--                               '2111426',
--                               '2112269',
--                               '2111423',
--                               '2112272',
--                               '2111387',
--                               '2112273',
--                               '2111388',
--                               '2112274',
--                               '2111391',
--                               '2223131',
--                               '2123131'
--                               )
-- and to_Date(aa.di_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') > 0                               
-- group by bb.item_no,di_Date
-- order by bb.item_no,di_Date;


-- cursor c_purchase_plan2 is
-- select  aa.item_no,  
--         to_Date(require_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') as DateDiff, 
--         sum(nvl(case when remainder_qty < 0 then 0 else remainder_qty end,0)) JumPre
-- from prf_details aa
-- inner join (select distinct item_no
--             from ztb_mrp_data_pck) bb  
-- on aa.item_no = bb.item_no
-- where aa.require_date > (select trim(sysdate) from dual) and to_Date(require_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') <= 90
--       and aa.remainder_qty <> 0 or remainder_qty is null
--       and to_Date(require_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') > 0 
--       and aa.item_no not in ('2211524',
--                               '2212349',
--                               '2211525',
--                               '2212350',
--                               '2211521',
--                               '2212353',
--                               '2211482',
--                               '2212354',
--                               '2211523',
--                               '2212356',
--                               '2211487',
--                               '2211485',
--                               '2212351',
--                               '2211486',
--                               '2212352',
--                               '2111425',
--                               '2112268',
--                               '2111426',
--                               '2112269',
--                               '2111423',
--                               '2112272',
--                               '2111387',
--                               '2112273',
--                               '2111388',
--                               '2112274',
--                               '2111391',
--                               '2223131',
--                               '2123131'
--                               )
-- group by aa.item_no,require_date
-- order by aa.item_no,require_date;

-- cursor c_arrive_plan is
-- select  aa.item_no,  
--         to_Date(eta,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') as DateDiff, 
--         sum(bal_qty) JumPre
-- from po_details aa
-- inner join (select distinct  item_no 
--             from ztb_mrp_data_pck) bb  
-- on aa.item_no = bb.item_no
-- where aa.eta > (select trim(sysdate) from dual) and to_Date(eta,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') <= 90
--       and aa.item_no not in ('2211524',
--                               '2212349',
--                               '2211525',
--                               '2212350',
--                               '2211521',
--                               '2212353',
--                               '2211482',
--                               '2212354',
--                               '2211523',
--                               '2212356',
--                               '2211487',
--                               '2211485',
--                               '2212351',
--                               '2211486',
--                               '2212352',
--                               '2111425',
--                               '2112268',
--                               '2111426',
--                               '2112269',
--                               '2111423',
--                               '2112272',
--                               '2111387',
--                               '2112273',
--                               '2111388',
--                               '2112274',
--                               '2111391',
--                               '2223131',
--                               '2123131'
--                               )
-- group by aa.item_no,eta
-- order by aa.item_no,eta;


-- cursor c_used_plan is
-- select 'update ztb_mrp_data_pck  set N_1 = ' ||
--        sum(n_1) || ' ,N_2 = ' ||
--        sum(n_2) || ' ,N_3 = ' ||
--        sum(n_3) || ' ,N_4 = ' ||
--        sum(n_4) || ' ,N_5 = ' ||
--        sum(n_5) || ' ,N_6 = ' ||
--        sum(n_6) || ' ,N_7 = ' ||
--        sum(n_7) || ' ,N_8 = ' ||
--        sum(n_8) || ' ,N_9 = ' ||
--        sum(n_9) || ' ,N_10 = ' ||
--        sum(n_10) || ' ,N_11 = ' ||
       
--         sum(n_11) || ' ,N_12 = ' ||
--        sum(n_12) || ' ,N_13 = ' ||
--        sum(n_13) || ' ,N_14 = ' ||
--        sum(n_14) || ' ,N_15 = ' ||
--        sum(n_15) || ' ,N_16 = ' ||
--        sum(n_16) || ' ,N_17 = ' ||
--        sum(n_17) || ' ,N_18 = ' ||
--        sum(n_18) || ' ,N_19 = ' ||
--        sum(n_19) || ' ,N_20 = ' ||
--        sum(n_20) || ' ,N_21 = ' ||
       
--         sum(n_21) || ' ,N_22 = ' ||
--        sum(n_22) || ' ,N_23 = ' ||
--        sum(n_23) || ' ,N_24 = ' ||
--        sum(n_24) || ' ,N_25 = ' ||
--        sum(n_25) || ' ,N_26 = ' ||
--        sum(n_26) || ' ,N_27 = ' ||
--        sum(n_27) || ' ,N_28 = ' ||
--        sum(n_28) || ' ,N_29 = ' ||
--        sum(n_29) || ' ,N_30 = ' ||
--        sum(n_30) || ' ,N_31 = ' ||
       
--         sum(n_31) || ' ,N_32 = ' ||
--        sum(n_32) || ' ,N_33 = ' ||
--        sum(n_33) || ' ,N_34 = ' ||
--        sum(n_34) || ' ,N_35 = ' ||
--        sum(n_35) || ' ,N_36 = ' ||
--        sum(n_36) || ' ,N_37 = ' ||
--        sum(n_37) || ' ,N_38 = ' ||
--        sum(n_38) || ' ,N_39 = ' ||
--        sum(n_39) || ' ,N_40 = ' ||
--        sum(n_40) || ' ,N_41 = ' ||
       
--         sum(n_41) || ' ,N_42 = ' ||
--        sum(n_42) || ' ,N_43 = ' ||
--        sum(n_43) || ' ,N_44 = ' ||
--        sum(n_44) || ' ,N_45 = ' ||
--        sum(n_45) || ' ,N_46 = ' ||
--        sum(n_46) || ' ,N_47 = ' ||
--        sum(n_47) || ' ,N_48 = ' ||
--        sum(n_48) || ' ,N_49 = ' ||
--        sum(n_49) || ' ,N_50 = ' ||
--        sum(n_50) || ' ,N_51 = ' ||
       
--         sum(n_51) || ' ,N_52 = ' ||
--        sum(n_52) || ' ,N_53 = ' ||
--        sum(n_53) || ' ,N_54 = ' ||
--        sum(n_54) || ' ,N_55 = ' ||
--        sum(n_55) || ' ,N_56 = ' ||
--        sum(n_56) || ' ,N_57 = ' ||
--        sum(n_57) || ' ,N_58 = ' ||
--        sum(n_58) || ' ,N_59 = ' ||
--        sum(n_59) || ' ,N_60 = ' ||
--        sum(n_60) || ' ,N_61 = ' ||
       
--         sum(n_61) || ' ,N_62 = ' ||
--        sum(n_62) || ' ,N_63 = ' ||
--        sum(n_63) || ' ,N_64 = ' ||
--        sum(n_64) || ' ,N_65 = ' ||
--        sum(n_65) || ' ,N_66 = ' ||
--        sum(n_66) || ' ,N_67 = ' ||
--        sum(n_67) || ' ,N_68 = ' ||
--        sum(n_68) || ' ,N_69 = ' ||
--        sum(n_69) || ' ,N_70 = ' ||
--        sum(n_70) || ' ,N_71 = ' ||
       
--         sum(n_71) || ' ,N_72 = ' ||
--        sum(n_72) || ' ,N_73 = ' ||
--        sum(n_73) || ' ,N_74 = ' ||
--        sum(n_74) || ' ,N_75 = ' ||
--        sum(n_75) || ' ,N_76 = ' ||
--        sum(n_76) || ' ,N_77 = ' ||
--        sum(n_77) || ' ,N_78 = ' ||
--        sum(n_78) || ' ,N_79 = ' ||
--        sum(n_79) || ' ,N_80 = ' ||
--        sum(n_80) || ' ,N_81 = ' ||
       
--         sum(n_81) || ' ,N_82 = ' ||
--        sum(n_82) || ' ,N_83 = ' ||
--        sum(n_83) || ' ,N_84 = ' ||
--        sum(n_84) || ' ,N_85 = ' ||
--        sum(n_85) || ' ,N_86 = ' ||
--        sum(n_86) || ' ,N_87 = ' ||
--        sum(n_87) || ' ,N_88 = ' ||
--        sum(n_88) || ' ,N_89 = ' ||
--        sum(n_89) || ' ,N_90 = ' ||
--        sum(n_90) || ' where no_id = 1 and item_no = ' || lower_item_no str
-- from (
--   select lower_item_no,
--          case when  mps_date = 1 then qty_p else 0 end n_1,
--          case when  mps_date = 2 then qty_p else 0 end n_2,
--          case when  mps_date = 3 then qty_p else 0 end n_3,
--          case when  mps_date = 4 then qty_p else 0 end n_4,
--          case when  mps_date = 5 then qty_p else 0 end n_5,
--          case when  mps_date = 6 then qty_p else 0 end n_6,
--          case when  mps_date = 7 then qty_p else 0 end n_7,
--          case when  mps_date = 8 then qty_p else 0 end n_8,
--          case when  mps_date = 9 then qty_p else 0 end n_9,
--          case when  mps_date = 10 then qty_p else 0 end n_10,
--          case when  mps_date = 11 then qty_p else 0 end n_11,
--          case when  mps_date = 12 then qty_p else 0 end n_12,
--          case when  mps_date = 13 then qty_p else 0 end n_13,
--          case when  mps_date = 14 then qty_p else 0 end n_14,
--          case when  mps_date = 15 then qty_p else 0 end n_15,
--          case when  mps_date = 16 then qty_p else 0 end n_16,
--          case when  mps_date = 17 then qty_p else 0 end n_17,
--          case when  mps_date = 18 then qty_p else 0 end n_18,
--          case when  mps_date = 19 then qty_p else 0 end n_19,
--          case when  mps_date = 20 then qty_p else 0 end n_20,
--          case when  mps_date = 21 then qty_p else 0 end n_21,
--          case when  mps_date = 22 then qty_p else 0 end n_22,
--          case when  mps_date = 23 then qty_p else 0 end n_23,
--          case when  mps_date = 24 then qty_p else 0 end n_24,
--          case when  mps_date = 25 then qty_p else 0 end n_25,
--          case when  mps_date = 26 then qty_p else 0 end n_26,
--          case when  mps_date = 27 then qty_p else 0 end n_27,
--          case when  mps_date = 28 then qty_p else 0 end n_28,
--          case when  mps_date = 29 then qty_p else 0 end n_29,
--          case when  mps_date = 30 then qty_p else 0 end n_30,
--          case when  mps_date = 31 then qty_p else 0 end n_31,
--          case when  mps_date = 32 then qty_p else 0 end n_32,
--          case when  mps_date = 33 then qty_p else 0 end n_33,
--          case when  mps_date = 34 then qty_p else 0 end n_34,
--          case when  mps_date = 35 then qty_p else 0 end n_35,
--          case when  mps_date = 36 then qty_p else 0 end n_36,
--          case when  mps_date = 37 then qty_p else 0 end n_37,
--          case when  mps_date = 38 then qty_p else 0 end n_38,
--          case when  mps_date = 39 then qty_p else 0 end n_39,
--          case when  mps_date = 40 then qty_p else 0 end n_40,
--          case when  mps_date = 41 then qty_p else 0 end n_41,
--          case when  mps_date = 42 then qty_p else 0 end n_42,
--          case when  mps_date = 43 then qty_p else 0 end n_43,
--          case when  mps_date = 44 then qty_p else 0 end n_44,
--          case when  mps_date = 45 then qty_p else 0 end n_45,
--          case when  mps_date = 46 then qty_p else 0 end n_46,
--          case when  mps_date = 47 then qty_p else 0 end n_47,
--          case when  mps_date = 48 then qty_p else 0 end n_48,
--          case when  mps_date = 49 then qty_p else 0 end n_49,
--          case when  mps_date = 50 then qty_p else 0 end n_50,
--          case when  mps_date = 51 then qty_p else 0 end n_51,
--          case when  mps_date = 52 then qty_p else 0 end n_52,
--          case when  mps_date = 53 then qty_p else 0 end n_53,
--          case when  mps_date = 54 then qty_p else 0 end n_54,
--          case when  mps_date = 55 then qty_p else 0 end n_55,
--          case when  mps_date = 56 then qty_p else 0 end n_56,
--          case when  mps_date = 57 then qty_p else 0 end n_57,
--          case when  mps_date = 58 then qty_p else 0 end n_58,
--          case when  mps_date = 59 then qty_p else 0 end n_59,
--          case when  mps_date = 60 then qty_p else 0 end n_60,
--          case when  mps_date = 61 then qty_p else 0 end n_61,
--          case when  mps_date = 62 then qty_p else 0 end n_62,
--          case when  mps_date = 63 then qty_p else 0 end n_63,
--          case when  mps_date = 64 then qty_p else 0 end n_64,
--          case when  mps_date = 65 then qty_p else 0 end n_65,
--          case when  mps_date = 66 then qty_p else 0 end n_66,
--          case when  mps_date = 67 then qty_p else 0 end n_67,
--          case when  mps_date = 68 then qty_p else 0 end n_68,
--          case when  mps_date = 69 then qty_p else 0 end n_69,
--          case when  mps_date = 70 then qty_p else 0 end n_70,
--          case when  mps_date = 71 then qty_p else 0 end n_71,
--          case when  mps_date = 72 then qty_p else 0 end n_72,
--          case when  mps_date = 73 then qty_p else 0 end n_73,
--          case when  mps_date = 74 then qty_p else 0 end n_74,
--          case when  mps_date = 75 then qty_p else 0 end n_75,
--          case when  mps_date = 76 then qty_p else 0 end n_76,
--          case when  mps_date = 77 then qty_p else 0 end n_77,
--          case when  mps_date = 78 then qty_p else 0 end n_78,
--          case when  mps_date = 79 then qty_p else 0 end n_79,
--          case when  mps_date = 80 then qty_p else 0 end n_80,
--          case when  mps_date = 81 then qty_p else 0 end n_81,
--          case when  mps_date = 82 then qty_p else 0 end n_82,
--          case when  mps_date = 83 then qty_p else 0 end n_83,
--          case when  mps_date = 84 then qty_p else 0 end n_84,
--          case when  mps_date = 85 then qty_p else 0 end n_85,
--          case when  mps_date = 86 then qty_p else 0 end n_86,
--          case when  mps_date = 87 then qty_p else 0 end n_87,
--          case when  mps_date = 88 then qty_p else 0 end n_88,
--          case when  mps_date = 89 then qty_p else 0 end n_89,
--          case when  mps_date = 90 then qty_p else 0 end n_90
         
--   from (
--   select case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end lower_item_no,
--          datediff(d,getdate(),mps_date) as mps_date,
--         CAST(sum(s.mps_qty) * quantity / quantity_base as decimal(18,2)) * (1 + (failure_rate /100))   qty_p
--   from mps_header r
--   inner join mps_details s 
--   on r.po_no = s.po_no and s.po_line_no = r.po_line_no
--   inner join (  
--             select * from structure s
--               ) st
--   on cast(st.upper_item_no as   varchar(10))+cast(level_no  as varchar(10)) = cast(r.item_no  as varchar(10))+cast(bom_level as  varchar(10))
--   inner join item it 
--   on case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end = it.item_no
--   where  mps_date > getdate() and datediff(d,getdate(),mps_date) <=90
--         -- and upper_item_no = '88680'
--   group by mps_date,case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end,quantity_base,quantity,failure_rate
  
--   )
-- ) group by lower_item_no;


-- --select case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end lower_item_no,
-- --       to_Date(mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')  mps_date,
-- --       CEIL(sum(s.mps_qty) * quantity / quantity_base) * (1 + (failure_rate /100))   qty_p
-- --from mps_header r
-- --inner join ztb_mps_details s 
-- --on r.po_no = s.po_no and s.po_line_no = r.po_line_no
-- --inner join (  
-- --          select * from structure s
-- --          inner join (
-- --          select max(level_no) level_nos, upper_item_no upper from structure
-- --          group by upper_item_no
-- --          )ss on s.upper_item_no = ss.upper and s.level_no = ss.level_nos
-- --
-- --            ) st
-- --on st.upper_item_no = r.item_no
-- --inner join item it 
-- --on case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end = it.item_no
-- --where  mps_date > (select trim(sysdate) from dual) and to_Date(mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') <=90
-- --      -- and upper_item_no = '88680'
-- --group by mps_date,case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end,quantity_base,quantity,failure_rate;


-- cursor c_mps (param1 in INTEGER) is
-- select 
--        sum(s.mps_qty)  qty, 
--        CEIL(sum(s.mps_qty) * quantity / quantity_base)   qty_p, 
--        mps_date,
--        st.lower_item_no,
--        it.description
-- from mps_header r
-- inner join mps_details s 
-- on r.po_no = s.po_no and s.po_line_no = r.po_line_no
-- inner join (  
--           select * from structure s
--             ) st
-- on st.upper_item_no||level_no = r.item_no||bom_level
-- inner join item it 
-- on st.lower_item_no = it.item_no
-- where mps_date = (select trim(sysdate+param1) from dual)
-- group by mps_date,lower_item_no,quantity_base,quantity,it.description;


-- cursor c_update is
-- select wo from ztb_log_kuraire;


-- BEGIN
-- delete from ztb_log_kuraire;
-- delete from ZTB_MRP_DATA_PCK;
  
  
  
--   open c_insert;
--     loop
--       fetch c_insert into c_item,c_description;
--       exit when c_insert%notfound;
--         begin
          
-- --          insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
-- --          select '0','TANGGAL',c_item,c_description
-- --          from dual;
-- --          
         
-- --          select sum(bal_qty) into v_qty from po_details where eta < (select trim(sysdate) from dual) and item_no = c_item;
-- --          EXCEPTION
-- --          WHEN NO_DATA_FOUND THEN
-- --          v_qty := 0;
-- --          END;
          
--           insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
--           select '1','PLAN',c_item,c_description
--           from dual;
          
--           insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
--           select '2','ARRIVE',c_item,c_description
--           from dual;
          
--           insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
--           select '3','PURCHASE',c_item,c_description
--           from dual;
          
--           insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
--           select '4','INVENTORY ',c_item,c_description
--           from dual;
          
--           insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
--           select '5','PURCHASE2 ',c_item,c_description
--           from dual;
          
--           v_qty := 0;
--       end;
--     end loop;
--   close c_insert;
 
 
--    open c_purchase_plan;
--     loop
--       fetch c_purchase_plan into c_item,c_day,c_qty;
--       exit when c_purchase_plan%notfound;
--         begin
          
--             v_str := 'update ztb_mrp_data_pck set N_' || c_day || ' = '|| nvl(c_qty,0) ||' WHERE item_no = '|| c_item ||' and no_id = 3 ';  
--             execute IMMEDIATE v_str;
--         end;
--     end loop;
--   close c_purchase_plan;
  
--   open c_purchase_plan2;
--     loop
--       fetch c_purchase_plan2 into c_item,c_day,c_qty;
--       exit when c_purchase_plan2%notfound;
--         begin
          
--             v_str := 'update ztb_mrp_data_pck set N_' || c_day || ' = '|| nvl(c_qty,0) ||' WHERE item_no = '|| c_item ||' and no_id =5 ';  
--             execute IMMEDIATE v_str;
--         end;
--     end loop;
--   close c_purchase_plan2;
  
  
--     open c_di_plan;
--     loop
--       fetch c_di_plan into c_item,c_day,c_qty;
--       exit when c_di_plan%notfound;
--         begin
          
--             v_str := 'update ztb_mrp_data_pck set N_' || c_day || ' = '|| nvl(c_qty,0) ||' WHERE item_no = '|| c_item ||' and no_id =5 ';  
--             execute IMMEDIATE v_str;
--         end;
--     end loop;
--   close c_di_plan;
-- --  
--    open c_arrive_plan;
--     loop
--       fetch c_arrive_plan into c_item,c_day,c_qty;
--       exit when c_arrive_plan%notfound;
--         begin
--             v_str := 'update ztb_mrp_data_pck set N_' || c_day || ' = '|| nvl(c_qty,0) ||' WHERE item_no = '|| c_item ||' and no_id = 2 ';  
--             execute IMMEDIATE v_str;
--         end;
--     end loop;
--   close c_arrive_plan;
-- --
--   open c_used_plan;
--     loop
--       fetch c_used_plan into c_wo;
--       exit when c_used_plan%notfound;
--         begin
-- --            insert into ztb_log_kuraire (wo) values (' nomor : ' || v_i || ' - ' || v_str  );
--             execute IMMEDIATE c_wo;
--         end;
--     end loop;
--   close c_used_plan;  
-- --
-- --  while v_days <= 90
-- --  loop
-- --  
-- --   select trim(sysdate+v_Days) into v_tgl from dual;
-- --      
-- --      if v_days = 1 then
-- --          v_str := 'update ZTB_MRP_DATA_PCK set n_' || v_days || ' =  (select w.this_inventory from whinventory w where w.item_no = ZTB_MRP_DATA_PCK.item_no) where no_id = 4' ;     
-- --          execute IMMEDIATE v_str;
-- --          -- insert into ztb_log_kuraire (wo) values (v_str);  
-- --          v_str := 'update ztb_MRP_data_pck set description =  description ||  (select ''( '' || cast(w.this_inventory as varchar(20))|| '') '' from whinventory w where w.item_no = ZTB_MRP_DATA_PCK.item_no) where no_id = 4 ';
-- --          execute IMMEDIATE v_str;
-- --         --  insert into ztb_log_kuraire (wo) values (v_str);
-- --
-- --      end if;
-- --      v_str := 'update ZTB_MRP_DATA_PCK set n_' || v_days || ' = (select sum(case when mm.no_id  = ''1'' then mm.n_' || v_days || '  * -1 else mm.n_' || v_days || ' * 1  end) from ZTB_MRP_DATA_PCK mm where mm.item_no = ZTB_MRP_DATA_PCK.item_no and mm.no_id <> 3) where no_id = 4';
-- --      execute IMMEDIATE v_str;
-- --      -- insert into ztb_log_kuraire (wo) values (v_str);
-- --      v_days1 := v_days1 + 1;
-- --      v_days := v_days + 1;
-- --  end loop;

-- END;





GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--exec zsp_mrp_pm

-- select * from ztb_mps_details where po_no = 'FI/20-163'

--select * from ZTB_MRP_DATA_PCK where item_no = '2225326'

-- select 
--        distinct
--        case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end lower_item_no,
--        it.description
-- select *       
-- from mps_header r
-- inner join ztb_mps_details s 
-- on r.po_no = s.po_no and s.po_line_no = r.po_line_no
-- inner join (  
--             select * from structure s
--             inner join (
--                           select max(level_no)level_no_max, upper_item_no as UPPER_ITEM_NOS from STRUCTURE 
--                           group by UPPER_ITEM_NO 
--               )ss on s.UPPER_ITEM_NO = ss.UPPER_ITEM_NOS and s.LEVEL_NO = ss.level_no_max
--               where LOWER_ITEM_NO = 2225326
--             ) st
-- on st.UPPER_ITEM_NO = r.ITEM_NO 
-- -- inner join item it 
-- -- on case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end = it.item_no
-- where   st.LOWER_ITEM_NO = 2225326
        
--         -- and upper_item_no = '88680'
--   group by mps_date,case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end,quantity_base,quantity,failure_rate
  

--  select case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end lower_item_no,
--         mps_date,
--         CAST(sum(s.mps_qty) * quantity / quantity_base as decimal(18,2)) * (1 + (failure_rate /100))   qty_p,
--         1
--    select *     
--   from mps_header r
--   inner join ZTB_MPS_DETAILS s 
--   on r.po_no = s.po_no and s.po_line_no = r.po_line_no
--   inner join (  
--             select * from structure s
--               ) st
--   on cast(st.upper_item_no as   varchar(10))+cast(level_no  as varchar(10)) = cast(r.item_no  as varchar(10))+cast(s.bom_level as  varchar(10))
--   inner join item it 
--   on case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end = it.item_no
--   where  mps_date > cast(getdate() as date) and datediff(d,getdate(),mps_date) <=90
        
--         -- and upper_item_no = '88680'
--   group by mps_date,case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end,quantity_base,quantity,failure_rate
  

--1232160	2020-07-27	5000000.000	5

-- update ztb_mrp_data_pck set N_1 = bb.jum from ztb_mrp_data_pck aa inner join (
--     select sum(qty_p) as jum,lower_item_no as item_no from tableplan_used                        
-- where  mps_date  = '2020-07-27' and flag = 5 group by lower_item_no )
-- bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 5

--select * from tableplan_used where  lower_item_no = '1232160' and mps_date = cast(getdate() as date) order by mps_date 
 



--    )bb on bb.ITEM_NO = aa.ITEM_NO where ztb_mrp_data_pck.no_id = 3

-- select * from ztb_mrp_data_pck order by item_no,no_id

--select * from ztb_mrp_data_pck where item_no in (select lower_item_no from tableplan_used)

CREATE PROCEDURE [dbo].[zsp_mrp_pm] AS


declare @table table(
   ID  int identity(1,1),
   lower_item_no int,
   desk NVARCHAR(100)
)

declare @table_produksi table (
    id INT IDENTITY(1,1),
    po_no varchar(30),
    po_line_no int,
    qty decimal(14,6)
)

declare @table_m_details table(
    ID INT IDENTITY(1,1),
    po_no varchar(30),
    po_line_no int,
    mps_date date,
    mps_qty decimal(14,6)
)

--##################################CACL MPS START #####################################

delete from ZTB_MPS_DETAILS 

insert into @table_produksi
select distinct r.po_no, r.po_line_no,  r.qty - isnull(qty_prod,isnull(cc.production,0)) - isnull(sum(s.mps_qty),0) Qty
from mps_header r
left outer join (select * from mps_details where mps_date > cast(getdate() + 90 as date)) s
on r.po_line_no = s.po_line_no and r.po_no = s.po_no
left outer join (select isnull(sum(case when slip_type = 87 then slip_quantity *-1 else SLIP_QUANTITY end ),0) Production, wo_no from production_income group by wo_no) cc
on r.work_order = cc.wo_no
left outer join (select wo_no,isnull(sum(qty_prod),0) qty_prod from 
                    (select distinct wo_no,plt_no,qty_prod,qty_order from ztb_m_plan where upload = 1 )aa group by wo_no)dd
on r.work_order = dd.wo_no
where r.status is not null --and r.work_order = '0010194-LR03C1-1'--and r.po_no = '0010194'--and r.po_no = '18FI075' and r.po_line_no = 1
      and r.po_no+ r.po_line_no not in (
        select distinct r.po_no+ r.po_line_no 
        from mps_header r
        left outer join mps_details s
        on r.po_no = s.po_no and r.po_line_no = s.po_line_no
        where s.po_no is null
      )
group by r.po_no, r.po_line_no,production,r.qty,qty_prod
having r.qty - isnull(cc.production,0) - isnull(sum(s.mps_qty),0) > 0;

declare @start int = 1
declare @end int

declare @start_x int = 1
declare @end_x int

select @end = isnull(max(ID),0)
from @table_produksi
 

declare @po_no VARCHAR(10)
declare @po_line_no INT
declare @qty decimal(16,4)  

declare @po_no_x VARCHAR(10)
declare @po_line_no_x INT
declare @mps_qty decimal(16,4)  
declare @mps_date date 

 insert into ZTB_MPS_DETAILS(PO_NO,PO_LINE_NO,MPS_QTY,MPS_DATE,UPLOAD_DATE)
 select PO_no,po_line_no,mps_qty,mps_date,cast(getdate() as date)
 from mps_details
 where mps_date between cast(getdate() as date) and cast(getdate()+90 as date)  
 and po_no + cast(po_line_no as varchar(10)) not in ( 
    select po_no + cast(po_line_no as varchar(10))
    from @table_produksi
 )



    
while @start <= @end BEGIN
    
    select @po_no = po_no,
           @po_line_no = po_line_no,
           @qty = qty
    from @table_produksi
    where id = @start

     insert into @table_m_details
     select po_no, po_line_no, mps_date, mps_qty from mps_details
     where mps_date between cast(getdate() as date) and cast(getdate()+90 as date)  
     and po_no = @po_no and PO_LINE_NO = @po_line_no
     order by mps_date;


    select @end_x = isnull(max(ID),0) 
    from @table_m_details
    
    while @start_x <= @end_x
    BEGIN
        select @po_no_x = po_no,
               @po_line_no_x = po_line_no,
               @mps_date = mps_date,
               @mps_qty = mps_qty
        from @table_m_details
        where id = @start_x

        IF  @qty > @mps_qty  BEGIN
            insert into ZTB_MPS_DETAILS(PO_NO,PO_LINE_NO,MPS_QTY,MPS_DATE,UPLOAD_DATE)
            select @PO_no,@po_line_no,@mps_qty,@mps_date,cast(getdate() as date)
            set @qty = @qty - @mps_qty
        END ELSE BEGIN
            insert into ZTB_MPS_DETAILS(PO_NO,PO_LINE_NO,MPS_QTY,MPS_DATE,UPLOAD_DATE)
            select @PO_no,@po_line_no,@qty,@mps_date,cast(getdate() as date)
            set @qty = 0
            set @start_x = @end_x
        END 


        set @start_x = @start_x + 1
    END
    
    delete from @table_m_details
    set @start = @start + 1
end 


--##################################CACL MPS END #####################################








insert into @table
select 
       distinct
       case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end lower_item_no,
       it.description
from mps_header r
inner join mps_details s 
on r.po_no = s.po_no and s.po_line_no = r.po_line_no
inner join (  
            select * from structure s
            inner join (
                          select max(level_no)level_no_max, upper_item_no as UPPER_ITEM_NOS from STRUCTURE 
                          group by UPPER_ITEM_NO 
              )ss on s.UPPER_ITEM_NO = ss.UPPER_ITEM_NOS and s.LEVEL_NO = ss.level_no_max
            ) st
on st.UPPER_ITEM_NO = r.ITEM_NO 
inner join item it 
on case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end = it.item_no
where   s.mps_date > cast(getdate() as date) 







-- create table tableplan_used (
--    ID  int identity(1,1),
--    lower_item_no int,
--    mps_date date,
--    qty_p bigint,
--    flag int
-- )
 
delete from tableplan_used

--inventory 4
insert into tableplan_used 
select bb.item_no,getdate(),isnull(bb.this_inventory,0) - isnull(cc.qty_not_approve,0)   ,4
            from whinventory bb
            inner join ((select distinct lower_item_no as item_no,desk
                        from @table))aa
            on aa.item_no = bb.ITEM_NO
            left outer join (   select item_no,sum(s.qty) qty_not_approve from mte_header r
                                        inner join mte_details s on r.slip_no = s.slip_no
                                        where r.approval_Date is null and slip_date > '01-JAN-19'
                                        group by item_no)cc
                    on  bb.item_no = cc.item_no

--purchase Plan 1
insert into tableplan_used 
select  aa.item_no,  
        require_date as DateDiff, 
        sum(isnull(case when remainder_qty < 0 then 0 else remainder_qty end,0)) JumPre,
        3
from prf_details aa
inner join (select distinct lower_item_no as item_no
            from @table) bb  
on aa.item_no = bb.item_no
where aa.require_date > cast(getdate() as date) and datediff(d, cast(getdate() as date),require_date) <= 90
and aa.item_no not in ('2211524',
                              '2212349',
                              '2211525',
                              '2212350',
                              '2211521',
                              '2212353',
                              '2211482',
                              '2212354',
                              '2211523',
                              '2212356',
                              '2211487',
                              '2211485',
                              '2212351',
                              '2211486',
                              '2212352',
                              '2111425',
                              '2112268',
                              '2111426',
                              '2112269',
                              '2111423',
                              '2112272',
                              '2111387',
                              '2112273',
                              '2111388',
                              '2112274',
                              '2111391',
                              '2223131',
                              '2123131'
                              )
group by aa.item_no,require_date
order by aa.item_no,require_date


--purchase plan 5

insert into tableplan_used
select  bb.item_no,  
        aa.di_date as DateDiff, 
        sum(qty) JumPre ,
        5
from di_header aa
inner join di_details bb
on aa.di_no = bb.di_no
where item_no in ('2211524',
                              '2212349',
                              '2211525',
                              '2212350',
                              '2211521',
                              '2212353',
                              '2211482',
                              '2212354',
                              '2211523',
                              '2212356',
                              '2211487',
                              '2211485',
                              '2212351',
                              '2211486',
                              '2212352',
                              '2111425',
                              '2112268',
                              '2111426',
                              '2112269',
                              '2111423',
                              '2112272',
                              '2111387',
                              '2112273',
                              '2111388',
                              '2112274',
                              '2111391',
                              '2223131',
                              '2123131'
                              )
and datediff(d,cast(getdate() as date),aa.di_date) > 0                               
group by bb.item_no,di_Date
order by bb.item_no,di_Date;


--purchase plan 2 -- 5

insert into tableplan_used
select  aa.item_no,  
        require_date as DateDiff, 
        sum(isnull(case when remainder_qty < 0 then 0 else remainder_qty end,0)) JumPre,
        5
from prf_details aa
inner join (select distinct item_no
            from ztb_mrp_data_pck) bb  
on aa.item_no = bb.item_no
where aa.require_date > cast(getdate() as date)
      and aa.remainder_qty <> 0 or remainder_qty is null
      and datediff(d, cast(getdate() as date),require_date ) between 0 and 90 
      and aa.item_no not in ('2211524',
                              '2212349',
                              '2211525',
                              '2212350',
                              '2211521',
                              '2212353',
                              '2211482',
                              '2212354',
                              '2211523',
                              '2212356',
                              '2211487',
                              '2211485',
                              '2212351',
                              '2211486',
                              '2212352',
                              '2111425',
                              '2112268',
                              '2111426',
                              '2112269',
                              '2111423',
                              '2112272',
                              '2111387',
                              '2112273',
                              '2111388',
                              '2112274',
                              '2111391',
                              '2223131',
                              '2123131'
                              )
group by aa.item_no,require_date
order by aa.item_no,require_date;



--arrive Plan--2
insert into tableplan_used 
select  aa.item_no,  
        eta  as DateDiff, 
        sum(bal_qty) JumPre,
        2
from po_details aa
inner join (select distinct lower_item_no as item_no
            from @table) bb  
on aa.item_no = bb.item_no
where aa.eta > cast(getdate() as date) and  datediff(d, cast(getdate() as date),eta )  <= 90
      and aa.item_no not in ('2211524',
                              '2212349',
                              '2211525',
                              '2212350',
                              '2211521',
                              '2212353',
                              '2211482',
                              '2212354',
                              '2211523',
                              '2212356',
                              '2211487',
                              '2211485',
                              '2212351',
                              '2211486',
                              '2212352',
                              '2111425',
                              '2112268',
                              '2111426',
                              '2112269',
                              '2111423',
                              '2112272',
                              '2111387',
                              '2112273',
                              '2111388',
                              '2112274',
                              '2111391',
                              '2223131',
                              '2123131'
                              )
group by aa.item_no,eta
order by aa.item_no,eta;



-- IF EXISTS(select * from tableplan_used) BEGIN
--     
-- END


 insert into tableplan_used
 select case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end lower_item_no,
        mps_date,
        CAST(sum(s.mps_qty) * quantity / quantity_base as decimal(18,2)) * (1 + (failure_rate /100))   qty_p,
        1
  from mps_header r
  inner join ZTB_MPS_DETAILS s 
  on r.po_no = s.po_no and s.po_line_no = r.po_line_no
  inner join (  
            select * from structure s
            inner join (
                          select max(level_no)level_no_max, upper_item_no as UPPER_ITEM_NOS from STRUCTURE 
                          group by UPPER_ITEM_NO 
              )ss on s.UPPER_ITEM_NO = ss.UPPER_ITEM_NOS and s.LEVEL_NO = ss.level_no_max
            ) st
  on st.UPPER_ITEM_NO = r.ITEM_NO 
  inner join item it 
  on case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end = it.item_no
  where  mps_date > cast(getdate() as date) and datediff(d,getdate(),mps_date) <=90
        
        -- and upper_item_no = '88680'
  group by mps_date,case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end,quantity_base,quantity,failure_rate
  
insert into ZTB_MRP_DATA_pck_DELETE
select getdate(),* from ZTB_MRP_DATA_pck
 
 delete from ztb_mrp_data_pck
    

 insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
 select '1','PLAN',lower_item_no,desk
 from @table 
          
          insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
          select '4','INVENTORY = '+  cast(isnull(bb.this_inventory,0) - isnull(cc.qty_not_approve,0) as varchar(30))  +'',bb.item_no,aa.desk
            from whinventory bb
            inner join ((select distinct lower_item_no as item_no,desk
                        from @table))aa
            on aa.item_no = bb.ITEM_NO
            left outer join (   select item_no,sum(s.qty) qty_not_approve from mte_header r
                                        inner join mte_details s on r.slip_no = s.slip_no
                                        where r.approval_Date is null and slip_date > '01-JAN-19'
                                        group by item_no)cc
                    on  bb.item_no = cc.item_no
          
          insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
          select '2','ARRIVE',lower_item_no,desk
          from @table 
          
          
          insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
          select '3','PURCHASE',lower_item_no,desk
          from @table
          
          
   
          
          
          insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
          select '5','PURCHASE2 ',lower_item_no,desk
          from @table 




DECLARE @date date = getdate()

  
    declare @qry nvarchar(max)
    set @start = 1
    set @end = 90
    declare @dateStart date 
    set @dateStart = dateadd(d,1,@date)
    
    
    while @start <= @end BEGIN
        
            set @date = dateadd(d,1,@date)  
            declare @tanggal NVARCHAR(10)
                
            set @qry = 'update ztb_mrp_data_pck set N_' + cast(@start as varchar(2)) + ' = bb.jum from ztb_mrp_data_pck aa inner join (select sum(qty_p) as jum,lower_item_no as item_no from tableplan_used
                        where  mps_date  = '''+ cast(@date as nvarchar(10)) +''' and flag = 1 group by lower_item_no )bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 1'
         
            exec (@qry)
            
            set @qry = ''

            set @qry = 'update ztb_mrp_data_pck set N_' + cast(@start as varchar(2)) + ' = bb.jum from ztb_mrp_data_pck aa inner join (select sum(qty_p) as jum,lower_item_no as item_no from tableplan_used
                        where  mps_date  = '''+ cast(@date as nvarchar(10)) +''' and flag = 3 group by lower_item_no )bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 3'
         
            exec (@qry)
            
            set @qry = ''

            set @qry = 'update ztb_mrp_data_pck set N_' + cast(@start as varchar(2)) + ' = bb.jum from ztb_mrp_data_pck aa inner join (select sum(qty_p) as jum,lower_item_no as item_no from tableplan_used
                        where  mps_date  = '''+ cast(@date as nvarchar(10)) +''' and flag = 2 group by lower_item_no )bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 2'
           
            exec (@qry)
            
            set @qry = ''

            set @qry = 'update ztb_mrp_data_pck set N_' + cast(@start as varchar(2)) + ' = bb.jum from ztb_mrp_data_pck aa inner join (select sum(qty_p) as jum,lower_item_no as item_no from tableplan_used
                        where  mps_date  = '''+ cast(@date as nvarchar(10)) +''' and flag = 5 group by lower_item_no )bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 5'
           
            exec (@qry)
            
            set @qry = ''  
            if(@start = 1) BEGIN
                    set @qry = 'update ztb_mrp_data_pck set N_' + cast(@start as varchar(2)) + ' = bb.jum from ztb_mrp_data_pck aa 
                            inner join (select bb.ITEM_NO,bb.THIS_INVENTORY - sum(case when no_id = 1 then N_1 * -1 else  N_1 end  ) as jum
                                        from ztb_mrp_data_pck aa
                                        inner join WHINVENTORY bb
                                        on aa.ITEM_NO = bb.ITEM_NO
                                        where NO_ID in (1,2)
                                              and bb.ITEM_no in (select lower_item_no from tableplan_used)
                                    group by bb.ITEM_NO,bb.THIS_INVENTORY  

                                )bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 4'
            
       exec (@qry)
      end ELSE BEGIN
                set @qry = 'update ztb_mrp_data_pck set N_' + cast(@start as varchar(2)) + ' = cc.jum from ztb_mrp_data_pck aa                     
                inner join (                       
                    select aa.item_no,juma + jumb as jum                                
                    from (                                
                        select item_no,sum(case when no_id = 1 then N_' + cast(@start as varchar(2)) + ' * -1 else  N_' + cast(@start as varchar(2)) + ' end  ) as jumb                                
                        from ztb_mrp_data_pck aa                                
                        where NO_ID in (1,2)                                
                        group by ITEM_NO)aa                                
                    inner join (                                
                        select item_no,sum(case when no_id = 1 then N_' + cast(@start-1 as varchar(2)) + ' * -1 else  N_' + cast(@start-1 as varchar(2)) + ' end  ) as juma                                
                        from ztb_mrp_data_pck aa                                
                        where NO_ID in (4)                               
                        group by ITEM_NO                                
                        )bb on aa.item_no  = bb.item_no                       
                )cc on cc.ITEM_NO = aa.ITEM_NO where no_id = 4'

                
       exec (@qry)
      END 
      
       

        set @start = @start + 1
    end

--drop table tableplan_used
--rop table tableplan_used

-- CREATE OR REPLACE PROCEDURE "PORDER"."ZSP_MRP_PM" is

-- v_i number := 0;
-- v_days number:= 1;
-- v_days1 number:= 0;
-- v_tgl VARCHAR(100);
-- v_qty number;
-- c_qty number;
-- c_qty_p number;
-- c_Date date;
-- c_item VARCHAR(100);
-- c_day number;
-- c_description varchar(100);
-- v_inventory number:=0;
-- v_purchase number:=0;
-- v_preq number:=0;
-- v_inventory_awal number :=0;
-- v_str varchar(3000);
-- c_wo varchar(3000);


-- cursor c_insert is
-- select 
--        distinct
--        case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end lower_item_no,
--        it.description
-- from mps_header r
-- inner join mps_details s 
-- on r.po_no = s.po_no and s.po_line_no = r.po_line_no
-- inner join (  
--           select * from structure 

--             ) st
-- on st.upper_item_no||level_no = r.item_no||bom_level
-- inner join item it 
-- on case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end = it.item_no
-- where   s.mps_date > (select trim(sysdate) from dual) --and upper_item_no = '88680'
-- ;


-- cursor c_purchase_plan is
-- select  aa.item_no,  
--         to_Date(require_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') as DateDiff, 
--         sum(nvl(case when remainder_qty < 0 then 0 else remainder_qty end,0)) JumPre
-- from prf_details aa
-- inner join (select distinct item_no
--             from ztb_mrp_data_pck) bb  
-- on aa.item_no = bb.item_no
-- where aa.require_date > (select trim(sysdate) from dual) and to_Date(require_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') <= 90
-- and aa.item_no not in ('2211524',
--                               '2212349',
--                               '2211525',
--                               '2212350',
--                               '2211521',
--                               '2212353',
--                               '2211482',
--                               '2212354',
--                               '2211523',
--                               '2212356',
--                               '2211487',
--                               '2211485',
--                               '2212351',
--                               '2211486',
--                               '2212352',
--                               '2111425',
--                               '2112268',
--                               '2111426',
--                               '2112269',
--                               '2111423',
--                               '2112272',
--                               '2111387',
--                               '2112273',
--                               '2111388',
--                               '2112274',
--                               '2111391',
--                               '2223131',
--                               '2123131'
--                               )
-- group by aa.item_no,require_date
-- order by aa.item_no,require_date;

-- cursor c_di_plan is
-- select  bb.item_no,  
--         to_Date(aa.di_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') as DateDiff, 
--         sum(qty) JumPre from di_header aa
-- inner join di_details bb
-- on aa.di_no = bb.di_no
-- where item_no in ('2211524',
--                               '2212349',
--                               '2211525',
--                               '2212350',
--                               '2211521',
--                               '2212353',
--                               '2211482',
--                               '2212354',
--                               '2211523',
--                               '2212356',
--                               '2211487',
--                               '2211485',
--                               '2212351',
--                               '2211486',
--                               '2212352',
--                               '2111425',
--                               '2112268',
--                               '2111426',
--                               '2112269',
--                               '2111423',
--                               '2112272',
--                               '2111387',
--                               '2112273',
--                               '2111388',
--                               '2112274',
--                               '2111391',
--                               '2223131',
--                               '2123131'
--                               )
-- and to_Date(aa.di_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') > 0                               
-- group by bb.item_no,di_Date
-- order by bb.item_no,di_Date;


-- cursor c_purchase_plan2 is
-- select  aa.item_no,  
--         to_Date(require_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') as DateDiff, 
--         sum(nvl(case when remainder_qty < 0 then 0 else remainder_qty end,0)) JumPre
-- from prf_details aa
-- inner join (select distinct item_no
--             from ztb_mrp_data_pck) bb  
-- on aa.item_no = bb.item_no
-- where aa.require_date > (select trim(sysdate) from dual) and to_Date(require_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') <= 90
--       and aa.remainder_qty <> 0 or remainder_qty is null
--       and to_Date(require_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') > 0 
--       and aa.item_no not in ('2211524',
--                               '2212349',
--                               '2211525',
--                               '2212350',
--                               '2211521',
--                               '2212353',
--                               '2211482',
--                               '2212354',
--                               '2211523',
--                               '2212356',
--                               '2211487',
--                               '2211485',
--                               '2212351',
--                               '2211486',
--                               '2212352',
--                               '2111425',
--                               '2112268',
--                               '2111426',
--                               '2112269',
--                               '2111423',
--                               '2112272',
--                               '2111387',
--                               '2112273',
--                               '2111388',
--                               '2112274',
--                               '2111391',
--                               '2223131',
--                               '2123131'
--                               )
-- group by aa.item_no,require_date
-- order by aa.item_no,require_date;

-- cursor c_arrive_plan is
-- select  aa.item_no,  
--         to_Date(eta,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') as DateDiff, 
--         sum(bal_qty) JumPre
-- from po_details aa
-- inner join (select distinct  item_no 
--             from ztb_mrp_data_pck) bb  
-- on aa.item_no = bb.item_no
-- where aa.eta > (select trim(sysdate) from dual) and to_Date(eta,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') <= 90
--       and aa.item_no not in ('2211524',
--                               '2212349',
--                               '2211525',
--                               '2212350',
--                               '2211521',
--                               '2212353',
--                               '2211482',
--                               '2212354',
--                               '2211523',
--                               '2212356',
--                               '2211487',
--                               '2211485',
--                               '2212351',
--                               '2211486',
--                               '2212352',
--                               '2111425',
--                               '2112268',
--                               '2111426',
--                               '2112269',
--                               '2111423',
--                               '2112272',
--                               '2111387',
--                               '2112273',
--                               '2111388',
--                               '2112274',
--                               '2111391',
--                               '2223131',
--                               '2123131'
--                               )
-- group by aa.item_no,eta
-- order by aa.item_no,eta;


-- cursor c_used_plan is
-- select 'update ztb_mrp_data_pck  set N_1 = ' ||
--        sum(n_1) || ' ,N_2 = ' ||
--        sum(n_2) || ' ,N_3 = ' ||
--        sum(n_3) || ' ,N_4 = ' ||
--        sum(n_4) || ' ,N_5 = ' ||
--        sum(n_5) || ' ,N_6 = ' ||
--        sum(n_6) || ' ,N_7 = ' ||
--        sum(n_7) || ' ,N_8 = ' ||
--        sum(n_8) || ' ,N_9 = ' ||
--        sum(n_9) || ' ,N_10 = ' ||
--        sum(n_10) || ' ,N_11 = ' ||
       
--         sum(n_11) || ' ,N_12 = ' ||
--        sum(n_12) || ' ,N_13 = ' ||
--        sum(n_13) || ' ,N_14 = ' ||
--        sum(n_14) || ' ,N_15 = ' ||
--        sum(n_15) || ' ,N_16 = ' ||
--        sum(n_16) || ' ,N_17 = ' ||
--        sum(n_17) || ' ,N_18 = ' ||
--        sum(n_18) || ' ,N_19 = ' ||
--        sum(n_19) || ' ,N_20 = ' ||
--        sum(n_20) || ' ,N_21 = ' ||
       
--         sum(n_21) || ' ,N_22 = ' ||
--        sum(n_22) || ' ,N_23 = ' ||
--        sum(n_23) || ' ,N_24 = ' ||
--        sum(n_24) || ' ,N_25 = ' ||
--        sum(n_25) || ' ,N_26 = ' ||
--        sum(n_26) || ' ,N_27 = ' ||
--        sum(n_27) || ' ,N_28 = ' ||
--        sum(n_28) || ' ,N_29 = ' ||
--        sum(n_29) || ' ,N_30 = ' ||
--        sum(n_30) || ' ,N_31 = ' ||
       
--         sum(n_31) || ' ,N_32 = ' ||
--        sum(n_32) || ' ,N_33 = ' ||
--        sum(n_33) || ' ,N_34 = ' ||
--        sum(n_34) || ' ,N_35 = ' ||
--        sum(n_35) || ' ,N_36 = ' ||
--        sum(n_36) || ' ,N_37 = ' ||
--        sum(n_37) || ' ,N_38 = ' ||
--        sum(n_38) || ' ,N_39 = ' ||
--        sum(n_39) || ' ,N_40 = ' ||
--        sum(n_40) || ' ,N_41 = ' ||
       
--         sum(n_41) || ' ,N_42 = ' ||
--        sum(n_42) || ' ,N_43 = ' ||
--        sum(n_43) || ' ,N_44 = ' ||
--        sum(n_44) || ' ,N_45 = ' ||
--        sum(n_45) || ' ,N_46 = ' ||
--        sum(n_46) || ' ,N_47 = ' ||
--        sum(n_47) || ' ,N_48 = ' ||
--        sum(n_48) || ' ,N_49 = ' ||
--        sum(n_49) || ' ,N_50 = ' ||
--        sum(n_50) || ' ,N_51 = ' ||
       
--         sum(n_51) || ' ,N_52 = ' ||
--        sum(n_52) || ' ,N_53 = ' ||
--        sum(n_53) || ' ,N_54 = ' ||
--        sum(n_54) || ' ,N_55 = ' ||
--        sum(n_55) || ' ,N_56 = ' ||
--        sum(n_56) || ' ,N_57 = ' ||
--        sum(n_57) || ' ,N_58 = ' ||
--        sum(n_58) || ' ,N_59 = ' ||
--        sum(n_59) || ' ,N_60 = ' ||
--        sum(n_60) || ' ,N_61 = ' ||
       
--         sum(n_61) || ' ,N_62 = ' ||
--        sum(n_62) || ' ,N_63 = ' ||
--        sum(n_63) || ' ,N_64 = ' ||
--        sum(n_64) || ' ,N_65 = ' ||
--        sum(n_65) || ' ,N_66 = ' ||
--        sum(n_66) || ' ,N_67 = ' ||
--        sum(n_67) || ' ,N_68 = ' ||
--        sum(n_68) || ' ,N_69 = ' ||
--        sum(n_69) || ' ,N_70 = ' ||
--        sum(n_70) || ' ,N_71 = ' ||
       
--         sum(n_71) || ' ,N_72 = ' ||
--        sum(n_72) || ' ,N_73 = ' ||
--        sum(n_73) || ' ,N_74 = ' ||
--        sum(n_74) || ' ,N_75 = ' ||
--        sum(n_75) || ' ,N_76 = ' ||
--        sum(n_76) || ' ,N_77 = ' ||
--        sum(n_77) || ' ,N_78 = ' ||
--        sum(n_78) || ' ,N_79 = ' ||
--        sum(n_79) || ' ,N_80 = ' ||
--        sum(n_80) || ' ,N_81 = ' ||
       
--         sum(n_81) || ' ,N_82 = ' ||
--        sum(n_82) || ' ,N_83 = ' ||
--        sum(n_83) || ' ,N_84 = ' ||
--        sum(n_84) || ' ,N_85 = ' ||
--        sum(n_85) || ' ,N_86 = ' ||
--        sum(n_86) || ' ,N_87 = ' ||
--        sum(n_87) || ' ,N_88 = ' ||
--        sum(n_88) || ' ,N_89 = ' ||
--        sum(n_89) || ' ,N_90 = ' ||
--        sum(n_90) || ' where no_id = 1 and item_no = ' || lower_item_no str
-- from (
--   select lower_item_no,
--          case when  mps_date = 1 then qty_p else 0 end n_1,
--          case when  mps_date = 2 then qty_p else 0 end n_2,
--          case when  mps_date = 3 then qty_p else 0 end n_3,
--          case when  mps_date = 4 then qty_p else 0 end n_4,
--          case when  mps_date = 5 then qty_p else 0 end n_5,
--          case when  mps_date = 6 then qty_p else 0 end n_6,
--          case when  mps_date = 7 then qty_p else 0 end n_7,
--          case when  mps_date = 8 then qty_p else 0 end n_8,
--          case when  mps_date = 9 then qty_p else 0 end n_9,
--          case when  mps_date = 10 then qty_p else 0 end n_10,
--          case when  mps_date = 11 then qty_p else 0 end n_11,
--          case when  mps_date = 12 then qty_p else 0 end n_12,
--          case when  mps_date = 13 then qty_p else 0 end n_13,
--          case when  mps_date = 14 then qty_p else 0 end n_14,
--          case when  mps_date = 15 then qty_p else 0 end n_15,
--          case when  mps_date = 16 then qty_p else 0 end n_16,
--          case when  mps_date = 17 then qty_p else 0 end n_17,
--          case when  mps_date = 18 then qty_p else 0 end n_18,
--          case when  mps_date = 19 then qty_p else 0 end n_19,
--          case when  mps_date = 20 then qty_p else 0 end n_20,
--          case when  mps_date = 21 then qty_p else 0 end n_21,
--          case when  mps_date = 22 then qty_p else 0 end n_22,
--          case when  mps_date = 23 then qty_p else 0 end n_23,
--          case when  mps_date = 24 then qty_p else 0 end n_24,
--          case when  mps_date = 25 then qty_p else 0 end n_25,
--          case when  mps_date = 26 then qty_p else 0 end n_26,
--          case when  mps_date = 27 then qty_p else 0 end n_27,
--          case when  mps_date = 28 then qty_p else 0 end n_28,
--          case when  mps_date = 29 then qty_p else 0 end n_29,
--          case when  mps_date = 30 then qty_p else 0 end n_30,
--          case when  mps_date = 31 then qty_p else 0 end n_31,
--          case when  mps_date = 32 then qty_p else 0 end n_32,
--          case when  mps_date = 33 then qty_p else 0 end n_33,
--          case when  mps_date = 34 then qty_p else 0 end n_34,
--          case when  mps_date = 35 then qty_p else 0 end n_35,
--          case when  mps_date = 36 then qty_p else 0 end n_36,
--          case when  mps_date = 37 then qty_p else 0 end n_37,
--          case when  mps_date = 38 then qty_p else 0 end n_38,
--          case when  mps_date = 39 then qty_p else 0 end n_39,
--          case when  mps_date = 40 then qty_p else 0 end n_40,
--          case when  mps_date = 41 then qty_p else 0 end n_41,
--          case when  mps_date = 42 then qty_p else 0 end n_42,
--          case when  mps_date = 43 then qty_p else 0 end n_43,
--          case when  mps_date = 44 then qty_p else 0 end n_44,
--          case when  mps_date = 45 then qty_p else 0 end n_45,
--          case when  mps_date = 46 then qty_p else 0 end n_46,
--          case when  mps_date = 47 then qty_p else 0 end n_47,
--          case when  mps_date = 48 then qty_p else 0 end n_48,
--          case when  mps_date = 49 then qty_p else 0 end n_49,
--          case when  mps_date = 50 then qty_p else 0 end n_50,
--          case when  mps_date = 51 then qty_p else 0 end n_51,
--          case when  mps_date = 52 then qty_p else 0 end n_52,
--          case when  mps_date = 53 then qty_p else 0 end n_53,
--          case when  mps_date = 54 then qty_p else 0 end n_54,
--          case when  mps_date = 55 then qty_p else 0 end n_55,
--          case when  mps_date = 56 then qty_p else 0 end n_56,
--          case when  mps_date = 57 then qty_p else 0 end n_57,
--          case when  mps_date = 58 then qty_p else 0 end n_58,
--          case when  mps_date = 59 then qty_p else 0 end n_59,
--          case when  mps_date = 60 then qty_p else 0 end n_60,
--          case when  mps_date = 61 then qty_p else 0 end n_61,
--          case when  mps_date = 62 then qty_p else 0 end n_62,
--          case when  mps_date = 63 then qty_p else 0 end n_63,
--          case when  mps_date = 64 then qty_p else 0 end n_64,
--          case when  mps_date = 65 then qty_p else 0 end n_65,
--          case when  mps_date = 66 then qty_p else 0 end n_66,
--          case when  mps_date = 67 then qty_p else 0 end n_67,
--          case when  mps_date = 68 then qty_p else 0 end n_68,
--          case when  mps_date = 69 then qty_p else 0 end n_69,
--          case when  mps_date = 70 then qty_p else 0 end n_70,
--          case when  mps_date = 71 then qty_p else 0 end n_71,
--          case when  mps_date = 72 then qty_p else 0 end n_72,
--          case when  mps_date = 73 then qty_p else 0 end n_73,
--          case when  mps_date = 74 then qty_p else 0 end n_74,
--          case when  mps_date = 75 then qty_p else 0 end n_75,
--          case when  mps_date = 76 then qty_p else 0 end n_76,
--          case when  mps_date = 77 then qty_p else 0 end n_77,
--          case when  mps_date = 78 then qty_p else 0 end n_78,
--          case when  mps_date = 79 then qty_p else 0 end n_79,
--          case when  mps_date = 80 then qty_p else 0 end n_80,
--          case when  mps_date = 81 then qty_p else 0 end n_81,
--          case when  mps_date = 82 then qty_p else 0 end n_82,
--          case when  mps_date = 83 then qty_p else 0 end n_83,
--          case when  mps_date = 84 then qty_p else 0 end n_84,
--          case when  mps_date = 85 then qty_p else 0 end n_85,
--          case when  mps_date = 86 then qty_p else 0 end n_86,
--          case when  mps_date = 87 then qty_p else 0 end n_87,
--          case when  mps_date = 88 then qty_p else 0 end n_88,
--          case when  mps_date = 89 then qty_p else 0 end n_89,
--          case when  mps_date = 90 then qty_p else 0 end n_90
         
--   from (
--   select case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end lower_item_no,
--          datediff(d,getdate(),mps_date) as mps_date,
--         CAST(sum(s.mps_qty) * quantity / quantity_base as decimal(18,2)) * (1 + (failure_rate /100))   qty_p
--   from mps_header r
--   inner join mps_details s 
--   on r.po_no = s.po_no and s.po_line_no = r.po_line_no
--   inner join (  
--             select * from structure s
--               ) st
--   on cast(st.upper_item_no as   varchar(10))+cast(level_no  as varchar(10)) = cast(r.item_no  as varchar(10))+cast(bom_level as  varchar(10))
--   inner join item it 
--   on case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end = it.item_no
--   where  mps_date > getdate() and datediff(d,getdate(),mps_date) <=90
--         -- and upper_item_no = '88680'
--   group by mps_date,case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end,quantity_base,quantity,failure_rate
  
--   )
-- ) group by lower_item_no;


-- --select case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end lower_item_no,
-- --       to_Date(mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')  mps_date,
-- --       CEIL(sum(s.mps_qty) * quantity / quantity_base) * (1 + (failure_rate /100))   qty_p
-- --from mps_header r
-- --inner join ztb_mps_details s 
-- --on r.po_no = s.po_no and s.po_line_no = r.po_line_no
-- --inner join (  
-- --          select * from structure s
-- --          inner join (
-- --          select max(level_no) level_nos, upper_item_no upper from structure
-- --          group by upper_item_no
-- --          )ss on s.upper_item_no = ss.upper and s.level_no = ss.level_nos
-- --
-- --            ) st
-- --on st.upper_item_no = r.item_no
-- --inner join item it 
-- --on case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end = it.item_no
-- --where  mps_date > (select trim(sysdate) from dual) and to_Date(mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') <=90
-- --      -- and upper_item_no = '88680'
-- --group by mps_date,case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end,quantity_base,quantity,failure_rate;


-- cursor c_mps (param1 in INTEGER) is
-- select 
--        sum(s.mps_qty)  qty, 
--        CEIL(sum(s.mps_qty) * quantity / quantity_base)   qty_p, 
--        mps_date,
--        st.lower_item_no,
--        it.description
-- from mps_header r
-- inner join mps_details s 
-- on r.po_no = s.po_no and s.po_line_no = r.po_line_no
-- inner join (  
--           select * from structure s
--             ) st
-- on st.upper_item_no||level_no = r.item_no||bom_level
-- inner join item it 
-- on st.lower_item_no = it.item_no
-- where mps_date = (select trim(sysdate+param1) from dual)
-- group by mps_date,lower_item_no,quantity_base,quantity,it.description;


-- cursor c_update is
-- select wo from ztb_log_kuraire;


-- BEGIN
-- delete from ztb_log_kuraire;
-- delete from ZTB_MRP_DATA_PCK;
  
  
  
--   open c_insert;
--     loop
--       fetch c_insert into c_item,c_description;
--       exit when c_insert%notfound;
--         begin
          
-- --          insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
-- --          select '0','TANGGAL',c_item,c_description
-- --          from dual;
-- --          
         
-- --          select sum(bal_qty) into v_qty from po_details where eta < (select trim(sysdate) from dual) and item_no = c_item;
-- --          EXCEPTION
-- --          WHEN NO_DATA_FOUND THEN
-- --          v_qty := 0;
-- --          END;
          
--           insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
--           select '1','PLAN',c_item,c_description
--           from dual;
          
--           insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
--           select '2','ARRIVE',c_item,c_description
--           from dual;
          
--           insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
--           select '3','PURCHASE',c_item,c_description
--           from dual;
          
--           insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
--           select '4','INVENTORY ',c_item,c_description
--           from dual;
          
--           insert into ztb_mrp_data_pck (NO_ID,description,item_no,item_desc)
--           select '5','PURCHASE2 ',c_item,c_description
--           from dual;
          
--           v_qty := 0;
--       end;
--     end loop;
--   close c_insert;
 
 
--    open c_purchase_plan;
--     loop
--       fetch c_purchase_plan into c_item,c_day,c_qty;
--       exit when c_purchase_plan%notfound;
--         begin
          
--             v_str := 'update ztb_mrp_data_pck set N_' || c_day || ' = '|| nvl(c_qty,0) ||' WHERE item_no = '|| c_item ||' and no_id = 3 ';  
--             execute IMMEDIATE v_str;
--         end;
--     end loop;
--   close c_purchase_plan;
  
--   open c_purchase_plan2;
--     loop
--       fetch c_purchase_plan2 into c_item,c_day,c_qty;
--       exit when c_purchase_plan2%notfound;
--         begin
          
--             v_str := 'update ztb_mrp_data_pck set N_' || c_day || ' = '|| nvl(c_qty,0) ||' WHERE item_no = '|| c_item ||' and no_id =5 ';  
--             execute IMMEDIATE v_str;
--         end;
--     end loop;
--   close c_purchase_plan2;
  
  
--     open c_di_plan;
--     loop
--       fetch c_di_plan into c_item,c_day,c_qty;
--       exit when c_di_plan%notfound;
--         begin
          
--             v_str := 'update ztb_mrp_data_pck set N_' || c_day || ' = '|| nvl(c_qty,0) ||' WHERE item_no = '|| c_item ||' and no_id =5 ';  
--             execute IMMEDIATE v_str;
--         end;
--     end loop;
--   close c_di_plan;
-- --  
--    open c_arrive_plan;
--     loop
--       fetch c_arrive_plan into c_item,c_day,c_qty;
--       exit when c_arrive_plan%notfound;
--         begin
--             v_str := 'update ztb_mrp_data_pck set N_' || c_day || ' = '|| nvl(c_qty,0) ||' WHERE item_no = '|| c_item ||' and no_id = 2 ';  
--             execute IMMEDIATE v_str;
--         end;
--     end loop;
--   close c_arrive_plan;
-- --
--   open c_used_plan;
--     loop
--       fetch c_used_plan into c_wo;
--       exit when c_used_plan%notfound;
--         begin
-- --            insert into ztb_log_kuraire (wo) values (' nomor : ' || v_i || ' - ' || v_str  );
--             execute IMMEDIATE c_wo;
--         end;
--     end loop;
--   close c_used_plan;  
-- --
-- --  while v_days <= 90
-- --  loop
-- --  
-- --   select trim(sysdate+v_Days) into v_tgl from dual;
-- --      
-- --      if v_days = 1 then
-- --          v_str := 'update ZTB_MRP_DATA_PCK set n_' || v_days || ' =  (select w.this_inventory from whinventory w where w.item_no = ZTB_MRP_DATA_PCK.item_no) where no_id = 4' ;     
-- --          execute IMMEDIATE v_str;
-- --          -- insert into ztb_log_kuraire (wo) values (v_str);  
-- --          v_str := 'update ztb_MRP_data_pck set description =  description ||  (select ''( '' || cast(w.this_inventory as varchar(20))|| '') '' from whinventory w where w.item_no = ZTB_MRP_DATA_PCK.item_no) where no_id = 4 ';
-- --          execute IMMEDIATE v_str;
-- --         --  insert into ztb_log_kuraire (wo) values (v_str);
-- --
-- --      end if;
-- --      v_str := 'update ZTB_MRP_DATA_PCK set n_' || v_days || ' = (select sum(case when mm.no_id  = ''1'' then mm.n_' || v_days || '  * -1 else mm.n_' || v_days || ' * 1  end) from ZTB_MRP_DATA_PCK mm where mm.item_no = ZTB_MRP_DATA_PCK.item_no and mm.no_id <> 3) where no_id = 4';
-- --      execute IMMEDIATE v_str;
-- --      -- insert into ztb_log_kuraire (wo) values (v_str);
-- --      v_days1 := v_days1 + 1;
-- --      v_days := v_days + 1;
-- --  end loop;

-- END;





GO
