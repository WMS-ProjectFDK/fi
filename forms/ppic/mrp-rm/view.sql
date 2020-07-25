SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[ZVW_MRP_HEAD] as
  select item_descm ITEM_TYPE,
case when sum(bb.N_1) = 0  then 0 else sum(aa.N_1)/sum(bb.N_1) end N_1,
case when sum(bb.N_2) = 0 then 0 else sum(aa.N_2)/sum(bb.N_2) end N_2,
case when sum(bb.N_3) = 0 then 0 else sum(aa.N_3)/sum(bb.N_3) end N_3,
case when sum(bb.N_4) = 0 then 0 else sum(aa.N_4)/sum(bb.N_4) end N_4,
case when sum(bb.N_5) = 0 then 0 else sum(aa.N_5)/sum(bb.N_5) end N_5,
case when sum(bb.N_6) = 0 then 0 else sum(aa.N_6)/sum(bb.N_6) end N_6,
case when sum(bb.N_7) = 0 then 0 else sum(aa.N_7)/sum(bb.N_7) end N_7,
case when sum(bb.N_8) = 0 then 0 else sum(aa.N_8)/sum(bb.N_8) end N_8,
case when sum(bb.N_9) = 0 then 0 else sum(aa.N_9)/sum(bb.N_9) end N_9,
case when sum(bb.N_10) = 0 then 0 else sum(aa.N_10)/sum(bb.N_10) end N_10,
case when sum(bb.N_11) = 0 then 0 else sum(aa.N_11)/sum(bb.N_11) end N_11,
case when sum(bb.N_12) = 0 then 0 else sum(aa.N_12)/sum(bb.N_12) end N_12,
case when sum(bb.N_13) = 0 then 0 else sum(aa.N_13)/sum(bb.N_13) end N_13,
case when sum(bb.N_14) = 0 then 0 else sum(aa.N_14)/sum(bb.N_14) end N_14,
case when sum(bb.N_15) = 0 then 0 else sum(aa.N_15)/sum(bb.N_15) end N_15,
case when sum(bb.N_16) = 0 then 0 else sum(aa.N_16)/sum(bb.N_16) end N_16,
case when sum(bb.N_17) = 0 then 0 else sum(aa.N_17)/sum(bb.N_17) end N_17,
case when sum(bb.N_18) = 0 then 0 else sum(aa.N_18)/sum(bb.N_18) end N_18,
case when sum(bb.N_19) = 0 then 0 else sum(aa.N_19)/sum(bb.N_19) end N_19,
case when sum(bb.N_20) = 0 then 0 else sum(aa.N_20)/sum(bb.N_20) end N_20,
case when sum(bb.N_21) = 0 then 0 else sum(aa.N_21)/sum(bb.N_21) end N_21,
case when sum(bb.N_22) = 0 then 0 else sum(aa.N_22)/sum(bb.N_22) end N_22,
case when sum(bb.N_23) = 0 then 0 else sum(aa.N_23)/sum(bb.N_23) end N_23,
case when sum(bb.N_24) = 0 then 0 else sum(aa.N_24)/sum(bb.N_24) end N_24,
case when sum(bb.N_25) = 0 then 0 else sum(aa.N_25)/sum(bb.N_25) end N_25,
case when sum(bb.N_26) = 0 then 0 else sum(aa.N_26)/sum(bb.N_26) end N_26,
case when sum(bb.N_27) = 0 then 0 else sum(aa.N_27)/sum(bb.N_27) end N_27,
case when sum(bb.N_28) = 0 then 0 else sum(aa.N_28)/sum(bb.N_28) end N_28,
case when sum(bb.N_29) = 0 then 0 else sum(aa.N_29)/sum(bb.N_29) end N_29,
case when sum(bb.N_30) = 0 then 0 else sum(aa.N_30)/sum(bb.N_30) end N_30,
case when sum(bb.N_31) = 0 then 0 else sum(aa.N_31)/sum(bb.N_31) end N_31,
case when sum(bb.N_32) = 0 then 0 else sum(aa.N_32)/sum(bb.N_32) end N_32,
case when sum(bb.N_33) = 0 then 0 else sum(aa.N_33)/sum(bb.N_33) end N_33,
case when sum(bb.N_34) = 0 then 0 else sum(aa.N_34)/sum(bb.N_34) end N_34,
case when sum(bb.N_35) = 0 then 0 else sum(aa.N_35)/sum(bb.N_35) end N_35,
case when sum(bb.N_36) = 0 then 0 else sum(aa.N_36)/sum(bb.N_36) end N_36,
case when sum(bb.N_37) = 0 then 0 else sum(aa.N_37)/sum(bb.N_37) end N_37,
case when sum(bb.N_38) = 0 then 0 else sum(aa.N_38)/sum(bb.N_38) end N_38,
case when sum(bb.N_39) = 0 then 0 else sum(aa.N_39)/sum(bb.N_39) end N_39,
case when sum(bb.N_40) = 0 then 0 else sum(aa.N_40)/sum(bb.N_40) end N_40,
case when sum(bb.N_41) = 0 then 0 else sum(aa.N_41)/sum(bb.N_41) end N_41,
case when sum(bb.N_42) = 0 then 0 else sum(aa.N_42)/sum(bb.N_42) end N_42,
case when sum(bb.N_43) = 0 then 0 else sum(aa.N_43)/sum(bb.N_43) end N_43,
case when sum(bb.N_44) = 0 then 0 else sum(aa.N_44)/sum(bb.N_44) end N_44,
case when sum(bb.N_45) = 0 then 0 else sum(aa.N_45)/sum(bb.N_45) end N_45,
case when sum(bb.N_46) = 0 then 0 else sum(aa.N_46)/sum(bb.N_46) end N_46,
case when sum(bb.N_47) = 0 then 0 else sum(aa.N_47)/sum(bb.N_47) end N_47,
case when sum(bb.N_48) = 0 then 0 else sum(aa.N_48)/sum(bb.N_48) end N_48,
case when sum(bb.N_49) = 0 then 0 else sum(aa.N_49)/sum(bb.N_49) end N_49,
case when sum(bb.N_50) = 0 then 0 else sum(aa.N_50)/sum(bb.N_50) end N_50,
case when sum(bb.N_51) = 0 then 0 else sum(aa.N_51)/sum(bb.N_51) end N_51,
case when sum(bb.N_52) = 0 then 0 else sum(aa.N_52)/sum(bb.N_52) end N_52,
case when sum(bb.N_53) = 0 then 0 else sum(aa.N_53)/sum(bb.N_53) end N_53,
case when sum(bb.N_54) = 0 then 0 else sum(aa.N_54)/sum(bb.N_54) end N_54,
case when sum(bb.N_55) = 0 then 0 else sum(aa.N_55)/sum(bb.N_55) end N_55,
case when sum(bb.N_56) = 0 then 0 else sum(aa.N_56)/sum(bb.N_56) end N_56,
case when sum(bb.N_57) = 0 then 0 else sum(aa.N_57)/sum(bb.N_57) end N_57,
case when sum(bb.N_58) = 0 then 0 else sum(aa.N_58)/sum(bb.N_58) end N_58,
case when sum(bb.N_59) = 0 then 0 else sum(aa.N_59)/sum(bb.N_59) end N_59,
case when sum(bb.N_60) = 0 then 0 else sum(aa.N_60)/sum(bb.N_60) end N_60,
case when sum(bb.N_61) = 0 then 0 else sum(aa.N_61)/sum(bb.N_61) end N_61,
case when sum(bb.N_62) = 0 then 0 else sum(aa.N_62)/sum(bb.N_62) end N_62,
case when sum(bb.N_63) = 0 then 0 else sum(aa.N_63)/sum(bb.N_63) end N_63,
case when sum(bb.N_64) = 0 then 0 else sum(aa.N_64)/sum(bb.N_64) end N_64,
case when sum(bb.N_65) = 0 then 0 else sum(aa.N_65)/sum(bb.N_65) end N_65,
case when sum(bb.N_66) = 0 then 0 else sum(aa.N_66)/sum(bb.N_66) end N_66,
case when sum(bb.N_67) = 0 then 0 else sum(aa.N_67)/sum(bb.N_67) end N_67,
case when sum(bb.N_68) = 0 then 0 else sum(aa.N_68)/sum(bb.N_68) end N_68,
case when sum(bb.N_69) = 0 then 0 else sum(aa.N_69)/sum(bb.N_69) end N_69,
case when sum(bb.N_70) = 0 then 0 else sum(aa.N_70)/sum(bb.N_70) end N_70,
case when sum(bb.N_71) = 0 then 0 else sum(aa.N_71)/sum(bb.N_71) end N_71,
case when sum(bb.N_72) = 0 then 0 else sum(aa.N_72)/sum(bb.N_72) end N_72,
case when sum(bb.N_73) = 0 then 0 else sum(aa.N_73)/sum(bb.N_73) end N_73,
case when sum(bb.N_74) = 0 then 0 else sum(aa.N_74)/sum(bb.N_74) end N_74,
case when sum(bb.N_75) = 0 then 0 else sum(aa.N_75)/sum(bb.N_75) end N_75,
case when sum(bb.N_76) = 0 then 0 else sum(aa.N_76)/sum(bb.N_76) end N_76,
case when sum(bb.N_77) = 0 then 0 else sum(aa.N_77)/sum(bb.N_77) end N_77,
case when sum(bb.N_78) = 0 then 0 else sum(aa.N_78)/sum(bb.N_78) end N_78,
case when sum(bb.N_79) = 0 then 0 else sum(aa.N_79)/sum(bb.N_79) end N_79,
case when sum(bb.N_80) = 0 then 0 else sum(aa.N_80)/sum(bb.N_80) end N_80,
case when sum(bb.N_81) = 0 then 0 else sum(aa.N_81)/sum(bb.N_81) end N_81,
case when sum(bb.N_82) = 0 then 0 else sum(aa.N_82)/sum(bb.N_82) end N_82,
case when sum(bb.N_83) = 0 then 0 else sum(aa.N_83)/sum(bb.N_83) end N_83,
case when sum(bb.N_84) = 0 then 0 else sum(aa.N_84)/sum(bb.N_84) end N_84,
case when sum(bb.N_85) = 0 then 0 else sum(aa.N_85)/sum(bb.N_85) end N_85,
case when sum(bb.N_86) = 0 then 0 else sum(aa.N_86)/sum(bb.N_86) end N_86,
case when sum(bb.N_87) = 0 then 0 else sum(aa.N_87)/sum(bb.N_87) end N_87,
case when sum(bb.N_88) = 0 then 0 else sum(aa.N_88)/sum(bb.N_88) end N_88,
case when sum(bb.N_89) = 0 then 0 else sum(aa.N_89)/sum(bb.N_89) end N_89,
case when sum(bb.N_90) = 0 then 0 else sum(aa.N_90)/sum(bb.N_90) end N_90

from
(select  ztb_mrp_Data.*,case Item_no 
        when '1170140' then 'CC ROD LR6'
        when '1130031' then 'CC ROD LR6'
        when '1170133' Then 'KOH'
        when '1170037' then 'KOH'
        when '1110064' then 'GASKET LR03'
        when '1110026' then 'GASKET LR03'
        when '1110046' then 'GASKET LR03'
        when '1120007' then 'WASHER LR6'
        when '1120026' then 'WASHER LR6'
        when '1120028' Then 'GASKET LR6'
        when '1120041' Then 'GASKET LR6'
        when '1130025' then 'GASKET LR6'
        when '1110031' then 'WASHER LR03'
        when '1110006' then 'WASHER LR03'
        when '1170113' then 'EMD TOSOH HH T2'
        when '1170130' then 'EMD TOSOH HH T2'
        when '1170118' then 'ZINC POWDER'
        when '1170120' then 'ZINC POWDER'
        when '1110060' then 'CATH CAN LR03 (COATED)  (S)'
        when '1110065' then 'CATH CAN LR03 (COATED)  (S)'
        when '1170155' then 'CATH CAN LR6(COAT)2.0'
        when '1120045' then 'CATH CAN LR6(COAT)2.0'
        when '1170161' then 'CARBOXIL/AQUPEC'
        when '1170139' then 'CARBOXIL/AQUPEC'
        else item_desc end item_descm from ztb_mrp_Data where   NO_ID = 4) aa
INNER JOIN
(select * from ztb_mrp_Data where   NO_ID = 8 )bb
on  aa.item_no = bb.item_no
--where item_descm = 'KOH'
group by item_descm;
GO
