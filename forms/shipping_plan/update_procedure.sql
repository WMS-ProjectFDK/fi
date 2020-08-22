SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--select newID()

--exec ZSP_SHIP_DETAIL_1 88868,3200000,'113/H/20','FI/20-150-LR6G07NC-5','A7DA680A-AB59-4930-A24D-D98573AA9DB6','S01771701',0,null,'B'

--select * from ztb_shipping_detail where item_no  =  '88868' and ppbe_no = '113/H/20'

--23296 msm = 
--113/H/20	NULL	88868	NULL	677376	32.6670000000	24	40 FEET	16581.6000000000	17942.3333000000	22.5216000000	FI/20-150-LR6G07NC-5	0	B	S01771701	0	NULL	FA668D0D-5966-40B2-9BEE-864816B0FD04


--exec ZSP_SHIP_DETAIL 69200,18000,'001/R/20','FIFJ/1914-LR03C1NC-1','S013550'

ALTER  procedure [dbo].[ZSP_SHIP_DETAIL] (
    @item_no int = null,
    @qty Bigint= null,
    @ppbe varchar(15)= null,
    @wo varchar(50)= null,
    @answer_no varchar(30)= null
)AS

BEGIN TRAN TT
declare @table table (
    id int IDENTITY(1,1),
    four_feet bigInt,
    two_feet bigint,
    PALLET bigint,
    pallet_pcs bigint,
    pallet_ctn bigint
)

declare @start int = 1,
        @end int =   1,
        @number bigint = 0,
        @four_feet bigint,
        @two_feet bigint,
        @pallet decimal(10,4),
        @pallet_pcs bigint,
        @pallet_ctn bigint  

insert into @table 
select four_feet*pallet_ctn FOUR_FEET ,
       two_feet*pallet_ctn TWO_FEET,
       CEILING(@qty/(pallet_pcs/ pallet_ctn)) PALLET,
       pallet_pcs,pallet_ctn 
from ztb_item 
where item_no = @item_no 
      and @qty > 0


select @end = count(*)
from @table

delete from ztb_shipping_detail where answer_no = @answer_no;

while @start <= @end BEGIN
    select @four_feet = four_feet,
           @two_feet  = two_feet,
           @pallet = pallet,
           @pallet_ctn = pallet_ctn,
           @pallet_pcs = pallet_pcs
    from @table
    where id = @start
    
 

    while @pallet > 0 BEGIN
          
          IF @pallet > @four_feet BEGIN
            
            set @number = @four_feet * (@pallet_pcs/@pallet_ctn);
            set @pallet = @pallet - @four_feet
             insert into ztb_shipping_detail(answer_no,
                                             WO_NO,
                                             PPBE_NO,
                                             ITEM_NO,
                                             QTY,
                                             PALLET,
                                             GROSS,
                                             NET,
                                             MSM,
                                             CARTON_NOT_FULL,
                                             CONTAINERS,
                                             container_value)
            select @answer_no,
                   @wo,
                   @ppbe,
                   @item_no,
                   @number,
                   @number/@pallet_pcs,
            case when @number< pallet_pcs then 
              (gw_pallet *  (floor((@number+pallet_pcs)/pallet_pcs)) +
              (
                ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
                ) / pallet_ctn) * (((@number+pallet_pcs)/(pallet_pcs/pallet_ctn)) - pallet_ctn) 
              )
              + case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
              ) - gw_pallet
            else
              gw_pallet *  floor(@number/pallet_pcs) +
              case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
                (
                ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
                ) / pallet_ctn) * 
                     case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
                    (@number / (pallet_pcs / pallet_ctn)) - (floor(@number/pallet_pcs) * pallet_ctn)
                    else 0 end
                ) + case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
              else 0 
              end
             end  as GrossWeight,
             -- END GROSS
              
             -- START NET
             @number * zt.NW_Pallet / pallet_pcs  as NetWeight, 
             -- START MSM
               floor( @number/zt.pallet_pcs)*(zt.panjang_pallet * zt.lebar_pallet * (zt.carton_height+150)/10000000) +
             case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
              zt.panjang_pallet*zt.lebar_pallet *
               (((zt.carton_height/(zt.pallet_ctn/step))*ceiling(
               (
                (@number / (pallet_pcs / pallet_ctn)) - (floor(@number/pallet_pcs) * pallet_ctn)
                 ) / step)+150))/10000000  
              else 0 end as MSM,



                
             -- END MSM
                       
             case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
                (@number / (pallet_pcs / pallet_ctn)) - (floor(@number/pallet_pcs) * pallet_ctn)
             else 0 end,
             '40 FEET',
             ((@number / (pallet_pcs/ pallet_ctn))/(four_feet* pallet_ctn))
             from item i 
             left outer join packing_information pi on pi.pi_no = i.pi_no 
             left outer join ztb_item zt on rtrim(ltrim(zt.item_no)) = i.item_no
             where  i.item_no = @item_no ;
          END ELSE IF @pallet > @two_feet and @pallet <= @four_feet BEGIN
             
             set @number = @pallet* (@pallet_pcs/@pallet_ctn); 
             set @pallet = 0;
            
            -- --#######################################################################################   
             insert into ztb_shipping_detail(answer_no,
                                             WO_NO,
                                             PPBE_NO,
                                             ITEM_NO,
                                             QTY,
                                             PALLET,
                                             GROSS,
                                             NET,
                                             MSM,
                                             CARTON_NOT_FULL,
                                             CONTAINERS,
                                             container_value)
            select @answer_no,
                   @wo,
                   @ppbe,
                   @item_no, 
                   @qty, 
                   @number/@pallet_pcs, 
                    
            -- START GROSS  
            case when @number< pallet_pcs then 
              (gw_pallet *  (floor((@number+pallet_pcs)/pallet_pcs)) +
                (
                  ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
                    ) / pallet_ctn) * (((@number+pallet_pcs)/(pallet_pcs/pallet_ctn)) - pallet_ctn) 
                ) + case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
              ) - gw_pallet
            else
             gw_pallet *  floor(@number/pallet_pcs) +
             case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
                (
                 ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
                  ) / pallet_ctn) * 
                  case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
                    (@number / (pallet_pcs / pallet_ctn)) - (floor(@number/pallet_pcs) * pallet_ctn)
                  else 0 end
                ) + case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
              else 0 end
              end  as GrossWeight,
            -- END GROSS
            
            -- START NET
              @number * zt.NW_Pallet / pallet_pcs  as NetWeight,
              
            -- START MSM
              floor( @number/zt.pallet_pcs)*(zt.panjang_pallet * zt.lebar_pallet * (zt.carton_height+150)/10000000) +
             case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
              zt.panjang_pallet*zt.lebar_pallet *
               (((zt.carton_height/(zt.pallet_ctn/step))*ceiling(
               (
                (@number / (pallet_pcs / pallet_ctn)) - (floor(@number/pallet_pcs) * pallet_ctn)
                 ) / step)+150))/10000000  
              else 0 end as MSM,
            -- END MSM
            
                case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
                  (@number / (pallet_pcs / pallet_ctn)) - (floor(@number/pallet_pcs) * pallet_ctn)
                else 0 end,
                '40 FEET',
                ((@number / (pallet_pcs/ pallet_ctn))/(four_feet* pallet_ctn))
            from item i 
            left outer join packing_information pi on pi.pi_no = i.pi_no 
            left outer join ztb_item zt on rtrim(ltrim(zt.item_no)) = i.item_no
            where  i.item_no = @item_no ;
          END ELSE BEGIN
            set @number = @pallet* (@pallet_pcs/@pallet_ctn);
            set @pallet = 0;
               
          --#######################################################################################   
          insert into ztb_shipping_detail(answer_no,
                                             WO_NO,
                                             PPBE_NO,
                                             ITEM_NO,
                                             QTY,
                                             PALLET,
                                             GROSS,
                                             NET,
                                             MSM,
                                             CARTON_NOT_FULL,
                                             CONTAINERS,
                                             container_value)
          select @answer_no,
                 @wo,
                 @ppbe,
                 @item_no, 
                 @qty, 
                 @number/@pallet_pcs,
                   
            -- START GROSS
            case when @number< pallet_pcs then 
              (gw_pallet *  (floor((@number+pallet_pcs)/pallet_pcs)) +
                (
                  ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
                   ) / pallet_ctn
                  ) * (((@number+pallet_pcs)/(pallet_pcs/pallet_ctn)) - pallet_ctn) 
                ) + case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
              ) - gw_pallet
            else
             gw_pallet *  floor(@number/pallet_pcs) +
             case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
              (
                ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
                ) / pallet_ctn) *  case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
                    (@number / (pallet_pcs / pallet_ctn)) - (floor(@number/pallet_pcs) * pallet_ctn)
                    else 0 end 
              ) + case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
             else 0 end
            end  as GrossWeight,
            --END GROSS
            
            -- START NET
             @number * zt.NW_Pallet / pallet_pcs  as NetWeight,
             
            -- START MSM
             floor( @number/zt.pallet_pcs)*(zt.panjang_pallet * zt.lebar_pallet * (zt.carton_height+150)/10000000) +
             case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
              zt.panjang_pallet*zt.lebar_pallet *
               (((zt.carton_height/(zt.pallet_ctn/step))*ceiling(
               (
                (@number / (pallet_pcs / pallet_ctn)) - (floor(@number/pallet_pcs) * pallet_ctn)
                 ) / step)+150))/10000000  
              else 0 end as MSM,
             -- END MSM
             
            case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
              (@number / (pallet_pcs / pallet_ctn)) - (floor(@number/pallet_pcs) * pallet_ctn)
            else 0 end,
            '20 FEET',
            ((@number / (pallet_pcs/ pallet_ctn))/(two_feet* pallet_ctn))
          from item i 
          left outer join packing_information pi on pi.pi_no = i.pi_no 
          left outer join ztb_item zt on rtrim(ltrim(zt.item_no)) = i.item_no
          where  i.item_no = @item_no ;
          END
    END
    

    set @start = @start + 1
END

COMMIT TRAN TT

-- CREATE OR REPLACE PROCEDURE "PORDER"."ZSP_SHIP_DETAIL" ( p_item_no   in varchar,p_qty   in varchar,p_ppbe   in varchar,p_wo   in varchar, p_answer_no in varchar)
-- as 

-- c_item_no varchar(100); 
-- v_number number := 0;
-- I number := 1;

-- c_item_no2 varchar(100); 
-- C_GR_no varchar(100);
-- c_amount NUMBER;
-- c_four_feet NUMBER;
-- c_two_feet NUMBER;
-- c_pallet number;
-- c_pallet_ctn NUMBER;
-- c_pallet_pcs number;



    
  

-- CURSOR C_ITEM IS
--   select four_feet*pallet_ctn FOUR_FEET ,two_feet*pallet_ctn TWO_FEET,CEIL(p_qty/(pallet_pcs/ pallet_ctn)) PALLET 
--   ,pallet_pcs,pallet_ctn from ztb_item where item_no = p_item_no;
      
-- BEGIN
-- v_number := 0;
-- delete from ztb_shipping_detail where answer_no = p_answer_no;

-- OPEN C_ITEM; 
--    LOOP 
--    FETCH C_Item into c_four_feet,c_two_feet,c_pallet,c_pallet_pcs,c_pallet_ctn; 
--       EXIT WHEN C_Item%notfound; 
--       BEGIN
--      WHILE c_pallet > 0 
--       LOOP
         
         
--          IF c_pallet > c_four_feet then
--             v_number := c_four_feet * (c_pallet_pcs/c_pallet_ctn);
--             c_pallet := c_pallet - c_four_feet;   
            
--       --#######################################################################################   
--             insert into ztb_shipping_detail(answer_no,WO_NO,PPBE_NO,ITEM_NO,QTY,PALLET,GROSS,NET,MSM,CARTON_NOT_FULL,CONTAINERS, container_value)
--             select    p_answer_no,
--                       p_wo,p_ppbe,
--                       p_item_no,
--                       v_number,
--                       v_number/c_pallet_pcs, 
                      
--             case when v_number< pallet_pcs then 
    
--             (gw_pallet *  (floor((v_number+pallet_pcs)/pallet_pcs))
--               +
            
--                 (
--                 ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
--                 ) / pallet_ctn) * (((v_number+pallet_pcs)/(pallet_pcs/pallet_ctn)) - pallet_ctn) )
--                 +case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end)
     
--               - gw_pallet
--              else
--              gw_pallet *  floor(v_number/pallet_pcs)
--               +
--               case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--                 (
--                 ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
--                 ) / pallet_ctn) * 
--                      case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--                     (v_number / (pallet_pcs / pallet_ctn)) - (floor(v_number/pallet_pcs) * pallet_ctn)
--                     else 0 end
                
--                 )
--                 +case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
--               else 0 
--               end
--               end  as GrossWeight,
--              v_number * zt.NW_Pallet / pallet_pcs  as NetWeight, 
--                 (floor( v_number/zt.pallet_pcs) *
--                  zt.panjang_pallet*zt.lebar_pallet* (zt.carton_height+150)/10000000)+
--                  zt.panjang_pallet*zt.lebar_pallet*
--                  ((zt.carton_height/(zt.pallet_ctn/step))*ceil(
--                  (
--                   case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--              (v_number / (pallet_pcs / pallet_ctn)) - (floor(v_number/pallet_pcs) * pallet_ctn)
--             else 0 end
                 
--                  )
                 
--                  /step)+150)/10000000 as MSM ,
                       
--                       case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--              (v_number / (pallet_pcs / pallet_ctn)) - (floor(v_number/pallet_pcs) * pallet_ctn)
--             else 0 end,
--                '40 FEET',
--                 ((v_number / (pallet_pcs/ pallet_ctn))/(four_feet* pallet_ctn))
--                 from item i 
--                 left outer join packing_information pi on pi.pi_no = i.pi_no 
--                 left outer join ztb_item zt on trim(zt.item_no) = i.item_no
--                 where  i.item_no = p_item_no ;
            
     
--       --#######################################################################################  
--          ELSIF c_pallet > c_two_feet and c_pallet <= c_four_feet THEN
--              v_number := c_pallet* (c_pallet_pcs/c_pallet_ctn); 
--              c_pallet := 0;
            
--           --#######################################################################################   
--             insert into ztb_shipping_detail(answer_no,WO_NO,PPBE_NO,ITEM_NO,QTY,PALLET,GROSS,NET,MSM,CARTON_NOT_FULL,CONTAINERS, container_value)
--             select    p_answer_no,    
--                       p_wo,p_ppbe,
--                       p_item_no,
--                       v_number,
--                       v_number/c_pallet_pcs, 
                      
--             case when v_number< pallet_pcs then 
    
--             (gw_pallet *  (floor((v_number+pallet_pcs)/pallet_pcs))
--               +
            
--                 (
--                 ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
--                 ) / pallet_ctn) * (((v_number+pallet_pcs)/(pallet_pcs/pallet_ctn)) - pallet_ctn) )
--                 +case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end)
     
--               - gw_pallet
--              else
--              gw_pallet *  floor(v_number/pallet_pcs)
--               +
--               case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--                 (
--                 ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
--                 ) / pallet_ctn) * 
--                     case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--                     (v_number / (pallet_pcs / pallet_ctn)) - (floor(v_number/pallet_pcs) * pallet_ctn)
--                     else 0 end
--                 )
--                 +case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
--               else 0 
--               end
--               end  as GrossWeight,
--              v_number * zt.NW_Pallet / pallet_pcs  as NetWeight, 
--                 (floor( v_number/zt.pallet_pcs) *
--        zt.panjang_pallet*zt.lebar_pallet* (zt.carton_height+150)/10000000)+
--        zt.panjang_pallet*zt.lebar_pallet*
--        ((zt.carton_height/(zt.pallet_ctn/step))*ceil(
--        (
--         case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--          (v_number / (pallet_pcs / pallet_ctn)) - (floor(v_number/pallet_pcs) * pallet_ctn)
--         else 0 end
             
--              )
             
--              /step)+150)/10000000 as MSM ,
                   
--                   case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--          (v_number / (pallet_pcs / pallet_ctn)) - (floor(v_number/pallet_pcs) * pallet_ctn)
--         else 0 end,
--             '40 FEET',
--              ((v_number / (pallet_pcs/ pallet_ctn))/(four_feet* pallet_ctn))
--             from item i 
--             left outer join packing_information pi on pi.pi_no = i.pi_no 
--             left outer join ztb_item zt on trim(zt.item_no) = i.item_no
--             where  i.item_no = p_item_no ;
       
--       --#######################################################################################  
         
--         ELSE  
--             v_number := c_pallet* (c_pallet_pcs/c_pallet_ctn);
--             c_pallet := 0;
               
--     --#######################################################################################   
--             insert into ztb_shipping_detail(answer_no,WO_NO,PPBE_NO,ITEM_NO,QTY,PALLET,GROSS,NET,MSM,CARTON_NOT_FULL,CONTAINERS, container_value)
--             select    p_answer_no,    
--                       p_wo,p_ppbe,
--                       p_item_no,
--                       v_number,
--                       v_number/c_pallet_pcs, 
                      
--             case when v_number< pallet_pcs then 
    
--             (gw_pallet *  (floor((v_number+pallet_pcs)/pallet_pcs))
--               +
            
--                 (
--                 ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
--                 ) / pallet_ctn) * (((v_number+pallet_pcs)/(pallet_pcs/pallet_ctn)) - pallet_ctn) )
--                 +case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end)
     
--               - gw_pallet
--              else
--              gw_pallet *  floor(v_number/pallet_pcs)
--               +
--               case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--                 (
--                 ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
--                 ) / pallet_ctn) *  case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--                     (v_number / (pallet_pcs / pallet_ctn)) - (floor(v_number/pallet_pcs) * pallet_ctn)
--                     else 0 end )
--                 +case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
--               else 0 
--               end
--               end  as GrossWeight,
--              v_number * zt.NW_Pallet / pallet_pcs  as NetWeight, 
--                 (floor( v_number/zt.pallet_pcs) *
--        zt.panjang_pallet*zt.lebar_pallet* (zt.carton_height+150)/10000000)+
--        zt.panjang_pallet*zt.lebar_pallet*
--        ((zt.carton_height/(zt.pallet_ctn/step))*ceil(
--        (
--         case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--    (v_number / (pallet_pcs / pallet_ctn)) - (floor(v_number/pallet_pcs) * pallet_ctn)
--   else 0 end
       
--        )
       
--        /step)+150)/10000000 as MSM ,
             
--             case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--    (v_number / (pallet_pcs / pallet_ctn)) - (floor(v_number/pallet_pcs) * pallet_ctn)
--   else 0 end,
--       '20 FEET',
--        c_pallet--((v_number / (pallet_pcs/ pallet_ctn))/(two_feet* pallet_ctn))
--       from item i 
--       left outer join packing_information pi on pi.pi_no = i.pi_no 
--       left outer join ztb_item zt on trim(zt.item_no) = i.item_no
--       where  i.item_no = p_item_no ;
--       --#######################################################################################
--    END IF;
      
--       END LOOP;
--       end;
--   END LOOP; 
-- CLOSE C_Item; 

GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--select newID()

--exec ZSP_SHIP_DETAIL_1 88868,3200000,'113/H/20','FI/20-150-LR6G07NC-5','A7DA680A-AB59-4930-A24D-D98573AA9DB6','S01771701',0,null,'B'

--select * from ztb_shipping_detail where item_no  =  '88868' and ppbe_no = '113/H/20'

--23296 msm = 
--113/H/20	NULL	88868	NULL	677376	32.6670000000	24	40 FEET	16581.6000000000	17942.3333000000	22.5216000000	FI/20-150-LR6G07NC-5	0	B	S01771701	0	NULL	FA668D0D-5966-40B2-9BEE-864816B0FD04

ALTER  procedure [dbo].[ZSP_SHIP_DETAIL_1] (
    @item_no int = null,
    @qty Bigint= null,
    @ppbe varchar(15)= null,
    @wo varchar(50)= null,
    @rowid varchar(50)= null,
    @answer_no varchar(30)= null,
    @tw bigint= null,
    @enr varchar(50)= null,
    @container_no varchar(30)= null
)AS


BEGIN TRAN TT
declare @table table (
    id int IDENTITY(1,1),
    four_feet bigInt,
    two_feet bigint,
    PALLET bigint,
    pallet_pcs bigint,
    pallet_ctn bigint
)

declare @start int = 1,
        @end int =   1,
        @number bigint = 0,
        @four_feet bigint,
        @two_feet bigint,
        @pallet decimal(10,4),
        @pallet_pcs bigint,
        @pallet_ctn bigint  

insert into @table 
select four_feet*pallet_ctn FOUR_FEET ,
       two_feet*pallet_ctn TWO_FEET,
       CEILING(@qty/(pallet_pcs/ pallet_ctn)) PALLET,
       pallet_pcs,pallet_ctn 
from ztb_item 
where item_no = @item_no 
      and @qty > 0


delete from ztb_shipping_detail where rowid = @rowid

select @end = count(*)
from @table

while @start <= @end BEGIN
    select @four_feet = four_feet,
           @two_feet  = two_feet,
           @pallet = pallet,
           @pallet_ctn = pallet_ctn,
           @pallet_pcs = pallet_pcs
    from @table
    where id = @start

    while @pallet > 0 BEGIN
          IF @pallet > @four_feet BEGIN
            set @number = @four_feet * (@pallet_pcs/@pallet_ctn);
            set @pallet = @pallet - @four_feet
             insert into ztb_shipping_detail(answer_no,
                                             WO_NO,
                                             PPBE_NO,
                                             ITEM_NO,
                                             QTY,
                                             PALLET,
                                             GROSS,
                                             NET,
                                             MSM,
                                             CARTON_NOT_FULL,
                                             CONTAINERS,
                                             container_value,
                                             container_no, 
                                             TW, 
                                             ENR,
                                             rowid)
            select @answer_no,
                   @wo,
                   @ppbe,
                   @item_no,
                   @number,
                   @number/@pallet_pcs,
            case when @number< pallet_pcs then 
              (gw_pallet *  (floor((@number+pallet_pcs)/pallet_pcs)) +
              (
                ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
                ) / pallet_ctn) * (((@number+pallet_pcs)/(pallet_pcs/pallet_ctn)) - pallet_ctn) 
              )
              + case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
              ) - gw_pallet
            else
              gw_pallet *  floor(@number/pallet_pcs) +
              case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
                (
                ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
                ) / pallet_ctn) * 
                     case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
                    (@number / (pallet_pcs / pallet_ctn)) - (floor(@number/pallet_pcs) * pallet_ctn)
                    else 0 end
                ) + case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
              else 0 
              end
             end  as GrossWeight,
             -- END GROSS
              
             -- START NET
             @number * zt.NW_Pallet / pallet_pcs  as NetWeight, 
             -- START MSM
            floor( @number/zt.pallet_pcs)*(zt.panjang_pallet * zt.lebar_pallet * (zt.carton_height+150)/10000000) +
             case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
              zt.panjang_pallet*zt.lebar_pallet *
               (((zt.carton_height/(zt.pallet_ctn/step))*ceiling(
               (
                (@number / (pallet_pcs / pallet_ctn)) - (floor(@number/pallet_pcs) * pallet_ctn)
                 ) / step)+150))/10000000  
              else 0 end
             as MSM,
             -- END MSM
                       
             case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
                (@number / (pallet_pcs / pallet_ctn)) - (floor(@number/pallet_pcs) * pallet_ctn)
             else 0 end,
             '40 FEET',
             ((@number / (pallet_pcs/ pallet_ctn))/(four_feet* pallet_ctn)),
             @container_no, 
             @tw, 
             @enr ,
             newid() 
             from item i 
             left outer join packing_information pi on pi.pi_no = i.pi_no 
             left outer join ztb_item zt on rtrim(ltrim(zt.item_no)) = i.item_no
             where  i.item_no = @item_no ;
          END ELSE IF @pallet > @two_feet and @pallet <= @four_feet BEGIN
             set @number = @pallet* (@pallet_pcs/@pallet_ctn); 
             set @pallet = 0;
            
            --#######################################################################################   
             insert into ztb_shipping_detail(answer_no,
                                             WO_NO,
                                             PPBE_NO,
                                             ITEM_NO,
                                             QTY,
                                             PALLET,
                                             GROSS,
                                             NET,
                                             MSM,
                                             CARTON_NOT_FULL,
                                             CONTAINERS,
                                             container_value,
                                             container_no, 
                                             TW, 
                                             ENR,
                                             rowid)
            select @answer_no,
                   @wo,
                   @ppbe,
                   @item_no, 
                   @qty, 
                   @number/@pallet_pcs, 
                    
            -- START GROSS  
            case when @number< pallet_pcs then 
              (gw_pallet *  (floor((@number+pallet_pcs)/pallet_pcs)) +
                (
                  ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
                    ) / pallet_ctn) * (((@number+pallet_pcs)/(pallet_pcs/pallet_ctn)) - pallet_ctn) 
                ) + case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
              ) - gw_pallet
            else
             gw_pallet *  floor(@number/pallet_pcs) +
             case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
                (
                 ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
                  ) / pallet_ctn) * 
                  case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
                    (@number / (pallet_pcs / pallet_ctn)) - (floor(@number/pallet_pcs) * pallet_ctn)
                  else 0 end
                ) + case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
              else 0 end
              end  as GrossWeight,
            -- END GROSS
            
            -- START NET
              @number * zt.NW_Pallet / pallet_pcs  as NetWeight,
              
            -- START MSM
             floor( @number/zt.pallet_pcs)*(zt.panjang_pallet * zt.lebar_pallet * (zt.carton_height+150)/10000000) +
             case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
              zt.panjang_pallet*zt.lebar_pallet *
               (((zt.carton_height/(zt.pallet_ctn/step))*ceiling(
               (
                (@number / (pallet_pcs / pallet_ctn)) - (floor(@number/pallet_pcs) * pallet_ctn)
                 ) / step)+150))/10000000  
              else 0 end
            
             
             as MSM,
            -- END MSM
            
                case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
                  (@number / (pallet_pcs / pallet_ctn)) - (floor(@number/pallet_pcs) * pallet_ctn)
                else 0 end,
                '40 FEET',
                ((@number / (pallet_pcs/ pallet_ctn))/(four_feet* pallet_ctn)),
                @container_no, 
                @tw, 
                @enr,
                newid()
            from item i 
            left outer join packing_information pi on pi.pi_no = i.pi_no 
            left outer join ztb_item zt on rtrim(ltrim(zt.item_no)) = i.item_no
            where  i.item_no = @item_no ;
          END ELSE BEGIN
            set @number = @pallet* (@pallet_pcs/@pallet_ctn);
            set @pallet = 0;
               
          --#######################################################################################   
          insert into ztb_shipping_detail(answer_no,
                                             WO_NO,
                                             PPBE_NO,
                                             ITEM_NO,
                                             QTY,
                                             PALLET,
                                             GROSS,
                                             NET,
                                             MSM,
                                             CARTON_NOT_FULL,
                                             CONTAINERS,
                                             container_value,
                                             container_no, 
                                             TW, 
                                             ENR,
                                             rowid)
          select @answer_no,
                 @wo,
                 @ppbe,
                 @item_no, 
                 @qty, 
                 @number/@pallet_pcs,
                   
            -- START GROSS
            case when @number< pallet_pcs then 
              (gw_pallet *  (floor((@number+pallet_pcs)/pallet_pcs)) +
                (
                  ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
                   ) / pallet_ctn
                  ) * (((@number+pallet_pcs)/(pallet_pcs/pallet_ctn)) - pallet_ctn) 
                ) + case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
              ) - gw_pallet
            else
             gw_pallet *  floor(@number/pallet_pcs) +
             case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
              (
                ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
                ) / pallet_ctn) *  case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
                    (@number / (pallet_pcs / pallet_ctn)) - (floor(@number/pallet_pcs) * pallet_ctn)
                    else 0 end 
              ) + case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
             else 0 end
            end  as GrossWeight,
            --END GROSS
            
            -- START NET
             @number * zt.NW_Pallet / pallet_pcs  as NetWeight,
             
            -- START MSM
            floor( @number/zt.pallet_pcs)*(zt.panjang_pallet * zt.lebar_pallet * (zt.carton_height+150)/10000000) +
             case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
              zt.panjang_pallet*zt.lebar_pallet *
               (((zt.carton_height/(zt.pallet_ctn/step))*ceiling(
               (
                (@number / (pallet_pcs / pallet_ctn)) - (floor(@number/pallet_pcs) * pallet_ctn)
                 ) / step)+150))/10000000  
              else 0 end
             as MSM,
             -- END MSM
             
            case when @number/pallet_pcs - floor(@number/pallet_pcs) > 0 then 
              (@number / (pallet_pcs / pallet_ctn)) - (floor(@number/pallet_pcs) * pallet_ctn)
            else 0 end,
            '20 FEET',
            ((@number / (pallet_pcs/ pallet_ctn))/(two_feet* pallet_ctn)),
            @container_no, 
            @tw, 
            @enr,
            newid()
          from item i 
          left outer join packing_information pi on pi.pi_no = i.pi_no 
          left outer join ztb_item zt on rtrim(ltrim(zt.item_no)) = i.item_no
          where  i.item_no = @item_no ;
          END
    END
    

    set @start = @start + 1
END

declare @msm decimal(10,4),
        @gw  decimal(10,4),
        @nw  decimal(10,4)

select @msm = sum(msm),@gw = sum(gross),@nw= sum(net)  from ztb_shipping_detail
where answer_no = @answer_no and ppbe_no = @ppbe;

update ztb_shipping_ins set msm = @msm,gw = @gw,nw = @nw
where answer_no = @answer_no and remarks = @ppbe;

COMMIT TRAN TT


-- CREATE OR REPLACE PROCEDURE "PORDER"."ZSP_SHIP_DETAIL_1" ( p_item_no   in varchar,p_qty   in varchar,p_ppbe   in varchar,p_wo   in varchar,p_container_no   in varchar,p_rowid in varchar,p_answer_no in varchar, p_tw in varchar, p_enr in varchar)
-- as 

-- c_item_no varchar(100); 
-- v_number number := 0;
-- I number := 1;
-- c_item_no2 varchar(100); 
-- C_GR_no varchar(100);
-- c_amount NUMBER;
-- c_four_feet NUMBER;
-- c_two_feet NUMBER;
-- c_pallet number;
-- c_pallet_ctn NUMBER;
-- c_pallet_pcs number;
-- v_msm number;
-- v_gw number;
-- v_nw number;

-- CURSOR C_ITEM IS
--   select four_feet*pallet_ctn FOUR_FEET ,two_feet*pallet_ctn TWO_FEET,CEIL(p_qty/(pallet_pcs/ pallet_ctn)) PALLET,
--   pallet_pcs,pallet_ctn from ztb_item where item_no = p_item_no and p_qty > 0;    
-- BEGIN



-- /* Otomatis menghapus wo yang sudah tidak ada di MPS */
-- --  delete from answer where 
-- --  answer_no in (
-- --                select answer_no from ztb_shipping_detail where ppbe_no = p_ppbe and wo_no not in (select work_order from mps_header) 
-- --                );
-- --  and answer_no not in (select answer_no from indication where answer_no = p_answer_no and inv_no is not null)

-- v_number := 0;
-- delete from ztb_shipping_detail where rowid = p_rowid;


-- OPEN C_ITEM; 
--    LOOP 
--     FETCH C_Item into c_four_feet,c_two_feet,c_pallet,c_pallet_pcs,c_pallet_ctn; 
--       EXIT WHEN C_Item%notfound ;   
--       BEGIN
--      WHILE c_pallet > 0 
--       LOOP
--          IF c_pallet > c_four_feet then
--             v_number := c_four_feet * (c_pallet_pcs/c_pallet_ctn);
--             c_pallet := c_pallet - c_four_feet;     
--          --#######################################################################################
--             insert into ztb_shipping_detail(answer_no,WO_NO,PPBE_NO,
--                                             ITEM_NO,QTY,PALLET,
--                                             GROSS,NET,MSM,
--                                             CARTON_NOT_FULL,CONTAINERS,container_value,
--                                             container_no, TW, ENR)
--             select p_answer_no,p_wo,p_ppbe,
--                    p_item_no,v_number,v_number/c_pallet_pcs,
                   
--             -- START GROSS    
--             --(14.5*floor(725760+20736)/20736) + 
--             case when v_number< pallet_pcs then 
--               (gw_pallet *  (floor((v_number+pallet_pcs)/pallet_pcs)) +
--               (
--                 ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
--                 ) / pallet_ctn) * (((v_number+pallet_pcs)/(pallet_pcs/pallet_ctn)) - pallet_ctn) 
--               )
--               + case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
--               ) - gw_pallet
--             else
--               gw_pallet *  floor(v_number/pallet_pcs) +
--               case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--                 (
--                 ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
--                 ) / pallet_ctn) * 
--                      case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--                     (v_number / (pallet_pcs / pallet_ctn)) - (floor(v_number/pallet_pcs) * pallet_ctn)
--                     else 0 end
--                 ) + case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
--               else 0 
--               end
--              end  as GrossWeight,
--              -- END GROSS
              
--              -- START NET
--              v_number * zt.NW_Pallet / pallet_pcs  as NetWeight, 
              
--              -- START MSM
--                 (floor( v_number/zt.pallet_pcs) * zt.panjang_pallet*zt.lebar_pallet* (zt.carton_height+150)/10000000)
--                 + zt.panjang_pallet*zt.lebar_pallet *
--                 ((zt.carton_height/(zt.pallet_ctn/step))*ceil(
--                 (
--                   case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--                     (v_number / (pallet_pcs / pallet_ctn)) - (floor(v_number/pallet_pcs) * pallet_ctn)
--                   else 0 end
--                 ) / step)+150
--                 )/10000000 as MSM,
--              -- END MSM
                       
--              case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--                 (v_number / (pallet_pcs / pallet_ctn)) - (floor(v_number/pallet_pcs) * pallet_ctn)
--              else 0 end,
--              '40 FEET',
--              ((v_number / (pallet_pcs/ pallet_ctn))/(four_feet* pallet_ctn)),
--              p_container_no, p_tw, p_enr
                
--              from item i 
--              left outer join packing_information pi on pi.pi_no = i.pi_no 
--              left outer join ztb_item zt on trim(zt.item_no) = i.item_no
--              where  i.item_no = p_item_no ;
            
--          --#######################################################################################  
--          ELSIF c_pallet > c_two_feet and c_pallet <= c_four_feet THEN
--              v_number := c_pallet* (c_pallet_pcs/c_pallet_ctn); 
--              c_pallet := 0;
            
--             --#######################################################################################   
--             insert into ztb_shipping_detail(answer_no,WO_NO,PPBE_NO,
--                                             ITEM_NO,QTY,PALLET,
--                                             GROSS,NET,MSM,
--                                             CARTON_NOT_FULL, CONTAINERS, container_value,
--                                             container_no, TW, ENR)
--             select p_answer_no,p_wo,p_ppbe,
--                    p_item_no, p_qty, v_number/c_pallet_pcs, 
                    
--             -- START GROSS  
--             case when v_number< pallet_pcs then 
--               (gw_pallet *  (floor((v_number+pallet_pcs)/pallet_pcs)) +
--                 (
--                   ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
--                     ) / pallet_ctn) * (((v_number+pallet_pcs)/(pallet_pcs/pallet_ctn)) - pallet_ctn) 
--                 ) + case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
--               ) - gw_pallet
--             else
--              gw_pallet *  floor(v_number/pallet_pcs) +
--              case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--                 (
--                  ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
--                   ) / pallet_ctn) * 
--                   case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--                     (v_number / (pallet_pcs / pallet_ctn)) - (floor(v_number/pallet_pcs) * pallet_ctn)
--                   else 0 end
--                 ) + case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
--               else 0 end
--               end  as GrossWeight,
--             -- END GROSS
            
--             -- START NET
--               v_number * zt.NW_Pallet / pallet_pcs  as NetWeight,
              
--             -- START MSM
--               (floor( v_number/zt.pallet_pcs) * zt.panjang_pallet*zt.lebar_pallet* (zt.carton_height+150)/10000000) +
--               zt.panjang_pallet*zt.lebar_pallet*
--               ((zt.carton_height/(zt.pallet_ctn/step))*ceil(
--               (
--                 case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--                   (v_number / (pallet_pcs / pallet_ctn)) - (floor(v_number/pallet_pcs) * pallet_ctn)
--                 else 0 end
--               ) / step)+150)/10000000 as MSM,
--             -- END MSM
            
--                 case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--                   (v_number / (pallet_pcs / pallet_ctn)) - (floor(v_number/pallet_pcs) * pallet_ctn)
--                 else 0 end,
--                 '40 FEET',
--                 ((v_number / (pallet_pcs/ pallet_ctn))/(four_feet* pallet_ctn)),
--                 p_container_no, p_tw, p_enr
--             from item i 
--             left outer join packing_information pi on pi.pi_no = i.pi_no 
--             left outer join ztb_item zt on trim(zt.item_no) = i.item_no
--             where  i.item_no = p_item_no ;
       
--       --#######################################################################################  
         
--         ELSE  
--             v_number := c_pallet* (c_pallet_pcs/c_pallet_ctn);
--             c_pallet := 0;
               
--           --#######################################################################################   
--           insert into ztb_shipping_detail(answer_no,WO_NO,PPBE_NO,
--                                           ITEM_NO,QTY,PALLET,
--                                           GROSS,NET,MSM,
--                                           CARTON_NOT_FULL,CONTAINERS, container_value,
--                                           container_no, TW, ENR)
--           select p_answer_no,p_wo,p_ppbe,
--                    p_item_no, p_qty, v_number/c_pallet_pcs,
                   
--             -- START GROSS
--             case when v_number< pallet_pcs then 
--               (gw_pallet *  (floor((v_number+pallet_pcs)/pallet_pcs)) +
--                 (
--                   ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
--                    ) / pallet_ctn
--                   ) * (((v_number+pallet_pcs)/(pallet_pcs/pallet_ctn)) - pallet_ctn) 
--                 ) + case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
--               ) - gw_pallet
--             else
--              gw_pallet *  floor(v_number/pallet_pcs) +
--              case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--               (
--                 ((GW_pallet -  case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end  
--                 ) / pallet_ctn) *  case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--                     (v_number / (pallet_pcs / pallet_ctn)) - (floor(v_number/pallet_pcs) * pallet_ctn)
--                     else 0 end 
--               ) + case when panjang_pallet = 110 then 35 else case when panjang_pallet = 100 then 27 else 25 end end
--              else 0 end
--             end  as GrossWeight,
--             --END GROSS
            
--             -- START NET
--              v_number * zt.NW_Pallet / pallet_pcs  as NetWeight,
             
--             -- START MSM
--              (floor( v_number/zt.pallet_pcs) * zt.panjang_pallet * zt.lebar_pallet * (zt.carton_height+150)/10000000)+
--              zt.panjang_pallet*zt.lebar_pallet *
--              (((zt.carton_height/(zt.pallet_ctn/step))*ceil(
--              (
--               case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--                 (v_number / (pallet_pcs / pallet_ctn)) - (floor(v_number/pallet_pcs) * pallet_ctn)
--               else 0 end
--              ) / step)+150))/10000000 as MSM,
--              -- END MSM
             
--             case when v_number/pallet_pcs - floor(v_number/pallet_pcs) > 0 then 
--               (v_number / (pallet_pcs / pallet_ctn)) - (floor(v_number/pallet_pcs) * pallet_ctn)
--             else 0 end,
--             '20 FEET',
--             ((v_number / (pallet_pcs/ pallet_ctn))/(two_feet* pallet_ctn)),
--             p_container_no, p_tw, p_enr
--           from item i 
--           left outer join packing_information pi on pi.pi_no = i.pi_no 
--           left outer join ztb_item zt on trim(zt.item_no) = i.item_no
--           where  i.item_no = p_item_no ;
--         --#######################################################################################
--         END IF;
--       END LOOP;
--       end;
--   END LOOP; 
-- CLOSE C_Item; 

-- select sum(msm),sum(gross),sum(net) into v_msm,v_gw,v_nw from ztb_shipping_detail
-- where answer_no = p_answer_no and ppbe_no = p_ppbe;

-- update ztb_shipping_ins set msm = v_msm,gw = v_gw,nw = v_nw
-- where answer_no = p_answer_no and remarks = p_ppbe;

-- END;
-- /
 

GO
