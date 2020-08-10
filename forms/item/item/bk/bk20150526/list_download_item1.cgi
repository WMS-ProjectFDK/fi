#!/usr/local/bin/perl

#
# Programed by R.Tsutsui 
# 2007/03/07
#
# Purpose : ITEM MASTER DOWNLOAD (EXCEL FORMAT)
#
# Reference File
#   SQL Library ： download_sql.pl
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
 require 'cgi-lib.pl' ;
 &ReadParse(*in) ;
 $ReadParse = "in" ;

 $ContentType = "application/vnd.ms-excel" ;
 print "Content-Disposition: attachment; filename=item_master.xls;";
 require '/pglosas/init.pl' ;
 require '\master/download/download_sql.pl' ;

#
# 
#
# Variable
#
 $word = $in{'word'} ;
 $word =~ tr/[a-z]/[A-Z]/ ;
 $stock_subject_code = $in{'stock_subject_code'} ;
 $section_code = $in{'section_code'} ;
 $class_1 = $in{'class_1'} ;
 $class_2 = $in{'class_2'} ;
 $class_3 = $in{'class_3'} ;
 $ipaddress = $in{'ipaddress'} ;

#
# SQL 
#
 $dbh = &db_open() ;

#
# Create SQL
#

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
      $sql .="  i.class_code                 ," ;
      $sql .="  c.class_1 || ' ' || class_2 || ' ' || class_3  class," ;
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
           # 日付は1900年日付システムを使用してシリアル値をセットし、表示はEXCELの表示形式で調整する
      $sql .="  trunc(i.upto_date    - to_date('1900/01/01','yyyy/mm/dd'))  upto_date," ;
      $sql .="  trunc(i.reg_date     - to_date('1900/01/01','yyyy/mm/dd'))  reg_date," ;
      $sql .="  trunc(i.receive_date - to_date('1900/01/01','yyyy/mm/dd'))  last_receive_date," ;
      $sql .="  trunc(i.issue_date   - to_date('1900/01/01','yyyy/mm/dd'))  last_issue_date," ;
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
      $sql .= " and   i.item_no in (select distinct item_no from list_item_up_wk where ipaddress = '$ipaddress') ";
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
 #print "$sql <BR>\n" ;
 #exit ;
     
 $cur = $dbh->prepare($sql) || &err("SQL err $DBI::err .... $DBI::errstr") ;
 $cur->execute() || &err("SQL Execute err $DBI::err .... $DBI::errstr") ;

for($i=0 ; $i<$cur->{NUM_OF_FIELDS} ; $i++) {
    push @fields_name, $cur->{NAME}->[$i] ;
}

while(@datas = $cur->fetchrow){
    $rec = {} ;
    for($i=0;$i<scalar(@datas);$i++){
        $cur->{NAME}->[$i] =~ tr/[A-Z]/[a-z]/ ;
        $datas[$i] =~ s/\"/&quot;/g ;
        $rec->{$cur->{NAME}->[$i]} = $datas[$i] ;
    }
    push @item_data,$rec ;
}

#-------------------------
# MAIN
#-------------------------
 #
 # STYLE SHEET
 #
print <<ENDOFTEXT ;
  <STYLE TYPE=text/css>
  <!--
     .varchar   { mso-number-format: '\@';           }
     .num_comma { mso-number-format: '#,##0';        }
     .decimal_6 { mso-number-format: '#,##0.000000'; }
     .decimal_4 { mso-number-format: '#,##0.0000';   }
     .date      { mso-number-format: 'dd/mm/yyyy';   }

     .table_title { background-color : '#ccccff';} 
     TD { white-space : nowrap; }   

  -->
  </STYLE>
ENDOFTEXT

 #
 # TITLE (Sheet Title)
 #
  print "<TABLE BORDER=0>\n" ;
  print "<TR>\n" ;
  print "  <TD COLSPAN=8>\n" ;
  print "     <CENTER><B>ITEM MASTER</B></CENTER>\n" ;
  print "  </TD>\n" ;
  print "</TR>\n" ;
  print "<TR>\n" ;
  print "  <TD>\n" ;
  print "  </TD>\n" ;
  print "</TR>\n" ;
  print "</TABLE>\n" ;

 #
 # TITLE (Fields Name)
 #
  print "<TABLE BORDER=1 Cellspacing=1 CellPadding=1>\n" ;
  print "<TR class=table_title>\n" ;
   foreach $ref(@fields_name) {
      print "<TD Align=Center>$ref<BR></TD>\n" ;
   }
  print "</TR>\n" ;

 #
 # DETAIL
 #
  $cnt = 3 ;
  foreach $ref(@item_data) {
      $cnt ++ ;
      print "<TR>\n" ;
      print "<TD>$ref->{item_no}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{item_code}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{stock_subject}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{item}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{section}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{maker}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{origin}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{item_flag}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{item_flag_name}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{description}<BR></TD>\n" ;
      print "<TD>$ref->{class_code}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{class}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{drawing_no}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{drawing_rev}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{catalog_no}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{applicable_model}<BR></TD>\n" ;
      print "<TD class=num_comma>$ref->{external_unit_number}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{qty_unit}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{stock_unit}<BR></TD>\n" ;
      print "<TD class=num_comma>$ref->{stock_rate}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{engineer_unit}<BR></TD>\n" ;
      print "<TD class=num_comma>$ref->{engineer_rate}<BR></TD>\n" ;
      print "<TD class=decimal_6>$ref->{weight}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{weight_unit}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{length_unit}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{currency}<BR></TD>\n" ;
      print "<TD class=decimal_6>$ref->{standard_price}<BR></TD>\n" ;
      print "<TD class=decimal_6>$ref->{next_term_price}<BR></TD>\n" ;
      print "<TD class=decimal_6>$ref->{suppliers_price}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{cost_subject_code}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{cost_process_code}<BR></TD>\n" ;
      print "<TD class=num_comma>$ref->{manufact_leadtime}<BR></TD>\n" ;
      print "<TD class=num_comma>$ref->{purchase_leadtime}<BR></TD>\n" ;
      print "<TD>$ref->{adjustment_leadtime}<BR></TD>\n" ;
      print "<TD>$ref->{llc}<BR></TD>\n" ;
      print "<TD>$ref->{reorder_point}<BR></TD>\n" ;
      print "<TD>$ref->{level_cont_key}<BR></TD>\n" ;
      print "<TD>$ref->{manufact_fail_rate}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{maker_flag}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{issue_policy}<BR></TD>\n" ;
      print "<TD class=num_comma>$ref->{issue_lot}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{order_policy}<BR></TD>\n" ;
      print "<TD class=num_comma>$ref->{safety_stock}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{stock_issue}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{item_type1}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{item_type2}<BR></TD>\n" ;
      print "<TD class=date>$ref->{upto_date}<BR></TD>\n" ;
      print "<TD class=date>$ref->{reg_date}<BR></TD>\n" ;
      print "<TD class=date>$ref->{last_receive_date}<BR></TD>\n" ;
      print "<TD class=date>$ref->{last_issue_date}<BR></TD>\n" ;
      print "<TD class=num_comma>$ref->{package_unit_number}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{unit_package}<BR></TD>\n" ;
      print "<TD class=decimal_4>$ref->{unit_price_org}<BR></TD>\n" ;
      print "<TD class=decimal_6>$ref->{unit_price_rate}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{unit_currency}<BR></TD>\n" ;
      print "<TD class=varchar>$ref->{upto_person_name}<BR></TD>\n" ;
      print "</TR>\n" ;
  }
  print "</TABLE>\n" ;
exit ;
1 ;

