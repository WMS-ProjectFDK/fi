SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE proc [dbo].[ZSP_MRP_MATERIAL] AS

--  IF EXISTS(select * from temptable) BEGIN
--     drop table temptable
--     drop table temptable2
--  END
--  create table  temptable  (
--      item_no int,
--      unit NVARCHAR(10),
--      deskripsi nvarchar(100),
--      item_type nvarchar(100),
     
--      min_days int,
--      max_days int
--  )

--   create table  temptable2 (
--      item_no int,
--      rata1 decimal(18,2),
--      rata2 decimal(18,2),
--      rata3 decimal(18,2),
--      rata4 decimal(18,2),
--      inventory bigint,
--      outstanding bigint
--  )

delete from temptable
delete from temptable2

 insert into temptable (item_no,deskripsi,item_type,min_days,max_days,unit)
 SELECT distinct z.item_no,aa.description,aa.item_type,isnull(min_days,0) min_days,isnull(max_days,0) as max_days,UNIT_PL
  FROM ztb_material_konversi z 
  inner join item i on i.ITEM_NO = z.ITEM_NO
  inner join unit on i.uom_q  = unit.unit_code
  inner join (select item_no, [description],
    case Item_no 
        when 1170140 then 'CC ROD LR6'
        when 1130031 then 'CC ROD LR6'
        when 1170133 Then 'KOH'
        when 1170037 then 'KOH'
        when 1110064 then 'GASKET LR03'
        when 1110026 then 'GASKET LR03'
        when 1120007 then 'WASHER LR6'
        when 1120026 then 'WASHER LR6'
        when 1120028 Then 'GASKET LR6'
        when 1120041 Then 'GASKET LR6'
        when 1130025 then 'GASKET LR6'
        when 1110031 then 'WASHER LR03'
        when 1110006 then 'WASHER LR03'
        when 1170113 then 'EMD TOSOH HH T2'
        when 1170130 then 'EMD TOSOH HH T2'
        when 1170118 then 'ZINC POWDER'
        when 1170120 then 'ZINC POWDER'
        when 1110060 then 'CATH CAN LR03 (COATED)  (S)'
        when 1110065 then 'CATH CAN LR03 (COATED)  (S)'
        when 1170155 then 'CATH CAN LR6(COAT)2.0'
        when 1120045 then 'CATH CAN LR6(COAT)2.0'
        when 1170161 then 'CARBOXIL/AQUPEC'
        when 1170139 then 'CARBOXIL/AQUPEC'
        else description end item_type
  from item )aa on z.item_no = aa.item_no
  left outer join (select isnull(min_days,0) min_days,isnull(max_days,0) max_days,item_no from ztb_config_rm) bb
  on aa.item_no = bb.item_no
 where z.item_no not in (
'1130014',
'1150006',
'1150007',
'1170003',
'1170004',
'1170008',
'1170014',
'1170021',
'1170025',
'1170026',
'1170039',
'1170045',
'1170047',
'1170071',
'1170072',
'1170093',
'1170094',
'1170097',
'1170109',
'1170112',
'1170119',
'1170129',
'1170135',
'1170139',
'1170141',
'1170160'
)

declare @date date

insert into ZTB_MRP_DATA_DELETE
select getdate(),* from ZTB_MRP_DATA


delete from ztb_mrp_data
select @date = getdate()

declare @revisiAssyPlan int


insert temptable2
select aa.item_no,sum(rata1),sum(rata2),sum(rata3),sum(rata4),sum(inventory),sum(outstanding) 
from (
select item_no,sum(qty * konversi / 1000) / count(tanggal) as rata1,0 as rata2,0 as rata3,0 as rata4,0 as inventory,0 as outstanding
from ztb_assy_plan aa
inner join ztb_material_konversi bb
    on aa.cell_type = bb.cell_type and aa.assy_line = bb.assy_line
where  tahun = year(@date) and bulan = month(@date)
    and used = 1
    --and revisi  = (select max(revisi) from ztb_assy_plan where  tahun = year(@date) and bulan = month(@date))
    and bb.cell_type is not null
group by bulan, tahun,item_no
union all
select item_no,0,sum(qty * konversi / 1000) / count(tanggal) as total,0,0,0,0
from ztb_assy_plan aa
inner join ztb_material_konversi bb
    on aa.cell_type = bb.cell_type and aa.assy_line = bb.assy_line
where  tahun = year(@date) and bulan = month(@date) + 1
    and used = 1
    --and revisi  = (select max(revisi) from ztb_assy_plan where  tahun = year(@date) and bulan = month(@date))
    and bb.cell_type is not null
group by bulan, tahun,item_no
union all
select item_no,0,0,sum(qty * konversi / 1000) / count(tanggal) as total,0,0,0
from ztb_assy_plan aa
inner join ztb_material_konversi bb
    on aa.cell_type = bb.cell_type and aa.assy_line = bb.assy_line
where  tahun = year(@date) and bulan = month(@date) + 2
    and used = 1
    --and revisi  = (select max(revisi) from ztb_assy_plan where  tahun = year(@date) and bulan = month(@date))
    and bb.cell_type is not null
group by bulan, tahun,item_no
union all
select item_no,0,0,0,sum(qty * konversi / 1000) / count(tanggal) as total,0,0
from ztb_assy_plan aa
inner join ztb_material_konversi bb
    on aa.cell_type = bb.cell_type and aa.assy_line = bb.assy_line
where  tahun = year(@date) and bulan = month(@date) + 3
    and used = 1
    --and revisi  = (select max(revisi) from ztb_assy_plan where  tahun = year(@date) and bulan = month(@date))
    and bb.cell_type is not null
group by bulan, tahun,item_no
union ALL
select whinventory.item_no,0,0,0,0,this_inventory - isnull(qty_not_approve,0),0  from whinventory 
        inner join temptable tt on tt.item_no = WHINVENTORY.ITEM_NO
        left outer join (   select item_no,sum(s.qty) qty_not_approve from mte_header r
                            inner join mte_details s on r.slip_no = s.slip_no
                            where r.approval_Date is null and slip_date > '01-JAN-19'
                            group by item_no)bb
        on  whinventory.item_no = bb.item_no 
union all
 select tt.item_no,0,0,0,0,0,(isnull(bal_qty,0)) 
 from po_details s
 inner join temptable tt on tt.item_no = s.ITEM_NO
   where eta < cast(getdate() as date) and eta > '01-JAN-18' 
)aa
group by item_no

    

    insert into ztb_mrp_data(item_no, item_desc,no_id,description,item_type)
    select tt.item_no,tt.deskripsi,1,'Daily Consumption Plan',tt.item_type
    from temptable tt
    inner join temptable2 tt2 on tt.item_no = tt2.item_no  

    insert into ztb_mrp_data(item_no, item_desc,no_id,description,item_type)
    select tt.item_no,tt.deskripsi,2,'Arrival FI Plan',tt.item_type
    from temptable tt
    inner join temptable2 tt2 on tt.item_no = tt2.item_no   

    insert into ztb_mrp_data(item_no, item_desc,no_id,description,item_type)
    select tt.item_no,tt.deskripsi,3,'Purchase Plan',tt.item_type
    from temptable tt
    inner join temptable2 tt2 on tt.item_no = tt2.item_no 

    insert into ztb_mrp_data(item_no, item_desc,no_id,description,item_type)
    select tt.item_no,tt.deskripsi,6,'ITO(Days)',tt.item_type
    from temptable tt
    inner join temptable2 tt2 on tt.item_no = tt2.item_no 

    insert into ztb_mrp_data(item_no, item_desc,no_id,description,item_type)
    select tt.item_no,tt.deskripsi,5,'Max ITO(Days)',tt.item_type
    from temptable tt
    inner join temptable2 tt2 on tt.item_no = tt2.item_no 

    insert into ztb_mrp_data(item_no, item_desc,no_id,description,item_type)
    select tt.item_no,tt.deskripsi,7,'Min ITO(Days)',tt.item_type
    from temptable tt
    inner join temptable2 tt2 on tt.item_no = tt2.item_no 

    insert into ztb_mrp_data(item_no, item_desc,no_id,description,item_type)
    select tt.item_no,tt.deskripsi,8,'Qty ITO/Day',tt.item_type
    from temptable tt
    inner join temptable2 tt2 on tt.item_no = tt2.item_no 


    insert into ztb_mrp_data(item_no, item_desc,no_id,[description],item_type)
    select tt.item_no,tt.deskripsi,4,'Inventory + Outstanding   '+ cast(tt2.inventory as varchar(10)) +' + '+ cast(isnull(tt2.outstanding,0) as varchar(10)) +' = '+  cast(tt2.inventory  + isnull(tt2.outstanding,0) as varchar(10)) +' '+ tt.unit +' )',tt.item_type
    from temptable tt
    inner join temptable2 tt2 on tt.item_no = tt2.item_no

    
    --select * from ztb_mrp_data where no_id in  (1,2,3,4,5,6,7,8)  order by item_no,no_id

    declare @start int 
    declare @end int
    declare @qry varchar(max)
    set @start = 1
    set @end = 90
    declare @qty bigint 
    declare @dateStart date 
    set @dateStart = dateadd(d,1,@date)

    while @start <= @end BEGIN
      set @date = dateadd(d,1,@date)

      set @qry = 'update ztb_mrp_data set N_' + cast(@start as varchar(2)) + ' = 0 from ztb_mrp_data zz  '
      exec (@qry)
      set @qry = ''
      set @qry = 'update ztb_mrp_data set N_' + cast(@start as varchar(2)) + ' = bb.total from ztb_mrp_data aa inner join  
                   (select isnull(sum(bal_qty),0) total,item_no from  PO_DETAILS where eta = ''' + cast(@date as varchar(10)) + '''                       
                  and bal_qty <> 0 group by item_no)bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 2'
      --select @qry   
      exec (@qry) 
      set @qry = ''

      set @qry = 'update ZTB_MRP_DATA set N_' + cast(@start as varchar(2)) + ' = bb.jum from ztb_mrp_data aa 
                   inner join (select isnull(sum(isnull(qty,0) ),0) as jum,item_no from prf_details aa inner join prf_header bb on aa.prf_no = bb.prf_no
                    where  require_date  = ''' + cast(@date as varchar(10)) + ''' group by item_no )bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 3'        
      
      exec (@qry) 

      set @qry = ''
      set @qry = 'update ZTB_MRP_DATA set N_' + cast(@start as varchar(2)) + ' = bb.jum from ztb_mrp_data aa 
                   inner join (select item_no,isnull(sum(qty/1000 * konversi ),0) jum
                      from ztb_assy_plan aa
                      inner join ztb_material_konversi bb
                      on aa.cell_type = bb.cell_type and aa.assy_line = bb.assy_line
                      where DATEFROMPARTS (tahun, bulan, tanggal) = ''' + cast(@date as varchar(10)) + '''
                      and used = 1
                      group by item_no
                   )bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 1'        
      exec (@qry) 

      set @qry = ''
      
      if(@start = 1) BEGIN
        set @qry = 'update ZTB_MRP_DATA set N_' + cast(@start as varchar(2)) + ' = bb.jum from ztb_mrp_data aa 
                   inner join (select bb.item_no,bb.inventory - sum(case when no_id = 1 then N_1 * -1 else  N_1 end  ) as jum
                            from ZTB_MRP_DATA aa
                            inner join temptable2 bb
                            on aa.ITEM_NO = bb.item_no
                            where NO_ID in (1,2)
                        group by bb.item_no,bb.inventory 
                       )bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 4'
      end ELSE BEGIN
        set @qry = 'update ZTB_MRP_DATA set N_' + cast(@start as varchar(2)) + ' = cc.jum from ztb_mrp_data aa                     
inner join (                       
    select aa.item_no,juma + jumb as jum                                
    from (                                
        select item_no,sum(case when no_id = 1 then N_' + cast(@start as varchar(2)) + ' * -1 else  N_' + cast(@start as varchar(2)) + ' end  ) as jumb                                
        from ZTB_MRP_DATA aa                                
        where NO_ID in (1,2)                                
        group by ITEM_NO)aa                                
    inner join (                                
        select item_no,sum(case when no_id = 1 then N_' + cast(@start-1 as varchar(2)) + ' * -1 else  N_' + cast(@start-1 as varchar(2)) + ' end  ) as juma                                
        from ZTB_MRP_DATA aa                                
        where NO_ID in (4)                               
         group by ITEM_NO                                
         )bb on aa.item_no  = bb.item_no                       
)cc on cc.ITEM_NO = aa.ITEM_NO where no_id = 4'
      END 
     exec(@qry) 
     
     set @qry = ''
     set @qry = 'update ZTB_MRP_DATA set N_' + cast(@start as varchar(2)) + ' = bb.jum from ztb_mrp_data aa 
                   inner join (
                       select item_no,min_days jum from temptable 
                   )bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 5' 
      exec(@qry) 
      set @qry = ''
      set @qry = 'update ZTB_MRP_DATA set N_' + cast(@start as varchar(2)) + ' = bb.jum from ztb_mrp_data aa 
                   inner join (
                       select item_no,max_days jum from temptable 
                   )bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 7'
     exec(@qry)
     
     set @qry = ''
      set @qry = 'update ZTB_MRP_DATA set N_' + cast(@start as varchar(2)) + ' = bb.jum from ztb_mrp_data aa 
                   inner join (
                       select item_no,rata' + cast(datediff(MONTH,@dateStart,@date) + 1 as varchar(2)) + ' jum from temptable2 
                   )bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 8'
    exec(@qry)

     set @qry = ''
      set @qry = 'update ZTB_MRP_DATA set N_' + cast(@start as varchar(2)) + ' = bb.jum from ztb_mrp_data aa 
                   inner join (
                       select aaa.item_no, case when aaa.rata = 0 then 0 else bbb.inventory / aaa.rata end as jum from
                       (
                         select item_no,rata' + cast(datediff(MONTH,@dateStart,@date) + 1 as varchar(2)) + ' as rata from temptable2 
                       )aaa inner join 
                       (
                           select item_no,N_' + cast(@start as varchar(2)) + ' as inventory from ZTB_MRP_DATA where no_id = 4
                       )bbb on aaa.item_no = bbb.item_no
                   )bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 6'
    exec(@qry)



      set @start = @start + 1
    END 




GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--exec zsp_mrp_pm

--1232160	2020-07-27	5000000.000	5

-- update ztb_mrp_data_pck set N_1 = bb.jum from ztb_mrp_data_pck aa inner join (
--     select sum(qty_p) as jum,lower_item_no as item_no from tableplan_used                        
-- where  mps_date  = '2020-07-27' and flag = 5 group by lower_item_no )
-- bb on bb.ITEM_NO = aa.ITEM_NO where no_id = 5

--select * from tableplan_used where  lower_item_no = '1232160' and mps_date = cast(getdate() as date) order by mps_date 
 



--    )bb on bb.ITEM_NO = aa.ITEM_NO where ztb_mrp_data_pck.no_id = 3

-- select * from ztb_mrp_data_pck order by item_no,no_id

--select * from ztb_mrp_data_pck where item_no in (select lower_item_no from tableplan_used)

ALTER PROCEDURE [dbo].[zsp_mrp_pm] AS


declare @table table(
   ID  int identity(1,1),
   lower_item_no int,
   desk NVARCHAR(100)
)

insert into @table
select 
       distinct
       case when st.lower_item_no > 70000000 then st.lower_item_no - 70000000 else st.lower_item_no end lower_item_no,
       it.description
from mps_header r
inner join mps_details s 
on r.po_no = s.po_no and s.po_line_no = r.po_line_no
inner join (  
          select * from structure 
            ) st
on cast(st.upper_item_no as   varchar(10))+cast(level_no  as varchar(10)) = cast(r.item_no  as varchar(10))+cast(bom_level as  varchar(10))
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
  inner join mps_details s 
  on r.po_no = s.po_no and s.po_line_no = r.po_line_no
  inner join (  
            select * from structure s
              ) st
  on cast(st.upper_item_no as   varchar(10))+cast(level_no  as varchar(10)) = cast(r.item_no  as varchar(10))+cast(bom_level as  varchar(10))
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
declare @start int 
    declare @end int
    declare @qry nvarchar(max)
    set @start = 1
    set @end = 90
    declare @qty bigint 
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
  where item_no = @item_no and customer_po_no = 'MRP' and h.prf_no <> @prf_no
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

