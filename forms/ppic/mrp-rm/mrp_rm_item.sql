SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE proc [dbo].[ZSP_MRP_MATERIAL_ITEM] (
    @item_no int = null
)AS

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
) and z.item_no = @item_no

declare @date date

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
)aa where  aa.item_no = @item_no
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
