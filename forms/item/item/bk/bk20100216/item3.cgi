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

#
# SQL 
#
 $dbh = &db_open() ;

#
# Create SQL
#
 $sql = &create_sql_item_master("excel") ;
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

