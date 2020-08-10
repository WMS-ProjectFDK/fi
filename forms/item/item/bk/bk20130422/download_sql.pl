#
# Programed by R.Tsutsui 
# 2007/03/07
#
# Purpose : SQL Library For Master Download
#
# Program Modification
#    2007/06/13  THAIユーザの要望により、最終登録者名の表示を追加した
#    2007/09/14  Sourceの共通化を行い、価格の少数点以下の表示桁数を変更 (standard_price , next_term_price , suppliers_price)
#                    ・少数点以下4桁→6桁への変更は、厦門ユーザからの要望
#                    ・DBは厦門のみ6桁に変更し、他拠点は変更しない(Sourceを共通化する為、表示だけ全拠点6桁で統一)
#    2007/09/14  Sourceの共通化を行い、表示項目の追加を行った。
#                    1. STOCK_ISSUE         → 受入即払出
#                    2. ENGINEER_UNIT       → 技術単位
#                    3. ENGINEER_RATE       → 技術単位率
#

#-------------------------
# Require init file 
#-------------------------
 require '/pglosas/init.pl' ;

#-------------------------
# Post Data
#-------------------------
foreach(keys (%in)){
   $$_ = $in{$_} ;
   #print "$_ = $in{$_}<BR>\n" ;
}

#-------------------------
# SQL Library 
#-------------------------
 #
 # ITEM MASTER
 #
  sub create_sql_item_master {
      local ($types) = @_ ;
      local ($sql) ;

      $sql  = " select ";
      $sql .="  i.item_no                    ," ;
      $sql .="  i.item_code                  ," ;
      $sql .="  st.stock_subject             ," ;
      $sql .="  i.item                       ," ;
      $sql .="  s.section                    ," ;
      $sql .="  i.mak            maker       ," ;
      $sql .="  u.country origin             ," ;
      $sql .="  i.item_flag                  ," ;
      $sql .="  itf.flag_name item_flag_name ," ;
      $sql .="  i.description                ," ;
      $sql .="  i.drawing_no                 ," ;
      $sql .="  i.drawing_rev                ," ;
      $sql .="  i.catalog_no                 ," ;
      $sql .="  i.applicable_model           ," ;
      $sql .="  i.external_unit_number       ," ;
      $sql .="  u1.unit qty_unit             ," ;
      $sql .="  u4.unit stock_unit           ," ;
      $sql .="  i.unit_stock_rate     stock_rate    ," ;
      $sql .="  u6.unit               engineer_unit ," ;
      $sql .="  i.unit_engineer_rate  engineer_rate ," ;
      $sql .="  i.weight                     ," ;
      $sql .="  u2.unit weight_unit          ," ;
      $sql .="  u3.unit length_unit          ," ;
      $sql .="  cu.curr_mark currency        ," ;
      $sql .="  i.standard_price             ," ;
      $sql .="  i.next_term_price            ," ;
      $sql .="  i.suppliers_price            ," ;
      $sql .="  i.cost_subject_code          ," ;
      $sql .="  i.cost_process_code          ," ;
      $sql .="  nvl(i.manufact_leadtime,0)   manufact_leadtime    ," ;
      $sql .="  nvl(i.purchase_leadtime,0)   purchase_leadtime    ," ;
      $sql .="  nvl(i.adjustment_leadtime,0) adjustment_leadtime  ," ;
      $sql .="  get_llc(i.item_no) llc       ," ;
      $sql .="  i.reorder_point              ," ;
      $sql .="  i.level_cont_key             ," ;
      $sql .="  i.manufact_fail_rate         ," ;
      $sql .="  i.maker_flag                 ," ;
      $sql .="  i.issue_policy               ," ;
      $sql .="  i.issue_lot                  ," ;
      $sql .="  i.order_policy               ," ;
      $sql .="  i.safety_stock               ," ;
      $sql .= " si.stock_issue               ," ;
      $sql .="  i.item_type1                 ," ;
      $sql .="  i.item_type2                 ," ; 
       if ($types eq "csv") {
           $sql .="  to_char(i.upto_date,'dd/mm/yyyy')    upto_date," ;
           $sql .="  to_char(i.reg_date,'dd/mm/yyyy')     reg_date," ;
           $sql .="  to_char(i.receive_date,'dd/mm/yyyy') last_receive_date," ;
           $sql .="  to_char(i.issue_date,'dd/mm/yyyy')   last_issue_date," ;
       } elsif ($types eq "excel") {
           # 日付は1900年日付システムを使用してシリアル値をセットし、表示はEXCELの表示形式で調整する
           $sql .="  trunc(i.upto_date    - to_date('1900/01/01','yyyy/mm/dd'))  upto_date," ;
           $sql .="  trunc(i.reg_date     - to_date('1900/01/01','yyyy/mm/dd'))  reg_date," ;
           $sql .="  trunc(i.receive_date - to_date('1900/01/01','yyyy/mm/dd'))  last_receive_date," ;
           $sql .="  trunc(i.issue_date   - to_date('1900/01/01','yyyy/mm/dd'))  last_issue_date," ;
       }
      $sql .="  i.package_unit_number        ," ;
      $sql .="  u5.unit            unit_package," ;
      $sql .="  i.unit_price_o     unit_price_org," ;
      $sql .="  i.unit_price_rate ," ;
      $sql .="  cu2.curr_mark      unit_currency," ;
      $sql .="  per.person         upto_person_name," ;
       chop($sql);
      $sql .= " from class c,";
      $sql .= "	   item i,";
      $sql .= "	   section s,";
      $sql .= "	   country u,";
      $sql .= "	   currency cu,";
      $sql .= "	   unit u1,";
      $sql .= "	   unit u2,";
      $sql .= "	   unit u3,";
      $sql .= "	   unit u4,";
      $sql .= "	   unit u5,";
      $sql .= "    unit u6,";
      $sql .= "	   stock_subject st,";
      $sql .= "	   currency cu2,";
      $sql .= "	   itemflag itf,";
      $sql .= "    person   per,";
      $sql .= "    stock_issue  si," ;
       chop($sql);
      $sql .= " where i.delete_type is null ";
      if($word ne ""){
          $sql .= " and  (    1=0 " ;
          $sql .= "        or i.item like '%$word%'";
          $sql .= "        or i.description like '%$word%'";
          $sql .= "        or i.item_no like '%$word%'";
          $sql .= "      ) " ;
      }
      $sql .= " and   i.origin_code = u.country_code (+) ";
      $sql .= " and   i.class_code = c.class_code (+) ";
      $sql .= " and   i.section_code = s.section_code (+) ";
      $sql .= " and   i.curr_code = cu.curr_code (+) ";
      $sql .= " and   i.uom_q = u1.unit_code (+) ";
      $sql .= " and   i.uom_w = u2.unit_code (+) ";
      $sql .= " and   i.uom_l = u3.unit_code (+) ";
      $sql .= " and   i.unit_stock = u4.unit_code (+) ";
      $sql .= " and   i.unit_package = u5.unit_code (+) ";
      $sql .= " and   i.unit_engineering = u6.unit_code(+) " ;
      $sql .= " and   i.stock_subject_code = st.stock_subject_code (+) ";
      $sql .= " and   i.unit_curr_code = cu2.curr_code (+) ";
      $sql .= " and   i.item_flag = itf.item_flag (+) ";
      $sql .= " and   i.upto_person_code = per.person_code(+) " ;
      $sql .= " and   i.stock_issue_flag = si.stock_issue_flag(+) " ;
      $sql .= " and   i.stock_subject_code = '$stock_subject_code' " if($stock_subject_code ne "");
      $sql .= " and   i.section_code = '$section_code' " if($section_code ne "");
      $sql .= " and   c.class_1 = '$class_1' " if($class_1 ne "");
      $sql .= " and   c.class_2 = '$class_2' " if($class_2 ne "");
      $sql .= " and   c.class_3 = '$class_3' " if($class_3 ne "");
      return ($sql) ;
  }

  1;
