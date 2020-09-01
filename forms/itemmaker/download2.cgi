#
# Programed by T.Ohsugi 
# 2000/08/04 
#     2003/12/04  在庫変換率を登録出来るように変更   By R.Tsutsui

#-------------------------
# Require init file
#-------------------------
$ContentType = "text/csv";
print "Content-Disposition: attachment; filename=bp_master.csv;";
require '/pglosas/init.pl' ;


#-------------------------
# POST
#-------------------------
$supplier_code = $in{'supplier_code'} ;
$section_code = $in{'section_code'} ;


#-------------------------
# DB OPEN
#-------------------------
 $dbh = &db_open() ;


#-------------------------
# MAIN
#-------------------------

  &RESULTS() ;


#-------------------------
# SUBROUTINE 
#-------------------------

#
#SEARCH
#
sub RESULTS{
  #ITEM MAKER 参照
   $sql  = " select distinct " ;
   $sql .=    "itm.create_date," ;
   $sql .=    "itm.operation_date," ;
   $sql .=    "itm.item_no," ;
   $sql .=    "itm.alter_procedure," ;
   $sql .=    "itm.supplier_code," ;
   $sql .=    "itm.manufact_fail_rate," ;
   $sql .=    "itm.delivery_allowable," ;
   $sql .=    "itm.delivery_standard," ;
   $sql .=    "itm.inspection_flag," ;
   $sql .=    "itm.purchase_leadtime," ;
   $sql .=    "itm.minimum_order_lot," ;
   $sql .=    "itm.split_order_lot," ;
   $sql .=    "itm.delivery_lot," ;
   $sql .=    "itm.estimate_price," ;
   $sql .=    "itm.material_cost," ;
   $sql .=    "itm.semifinish_cost," ;
   $sql .=    "itm.manufacturing_cost," ;
   $sql .=    "itm.adjustment_price," ;
   $sql .=    "itm.trial_sample_price," ;
   $sql .=    "itm.curr_code," ;
   $sql .=    "itm.tran_first_date," ;
   $sql .=    "itm.tran_last_date," ;
   $sql .=    "com.company  supplier," ;
   $sql .=    "i.item," ;
   $sql .=    "i.description," ;
   $sql .=    "i.drawing_no," ;
   $sql .=    "i.drawing_rev," ;
   $sql .=    "i.catalog_no," ;
   $sql .=    "i.standard_price," ;
   $sql .=    "i.suppliers_price," ;
   $sql .=    "i.section_code," ;
   $sql .=    "s.section," ;
   $sql .=    "st.stock_subject," ;
   $sql .=    "cur.curr_mark," ;
   $sql .=    "itm.unit_purchase_rate," ;
   $sql .=    "u.unit  purchase_unit," ;
   chop($sql) ;
   $sql .= " from itemmaker itm," ;
   $sql .= "      company   com," ;
   $sql .= "      item      i," ;
   $sql .= "      stock_subject st," ;
   $sql .= "      section s," ;
   $sql .= "      currency  cur," ;
   $sql .= "      unit u," ;
   chop($sql) ;
   $sql .= " where itm.supplier_code = com.company_code (+) " ;
   $sql .= "   and itm.item_no = i.item_no (+) " ;
   $sql .= "   and i.stock_subject_code = st.stock_subject_code(+) " ;
   $sql .= "   and itm.curr_code = cur.curr_code(+) " ;
   $sql .= "   and i.section_code = s.section_code (+) " ;
   $sql .= "   and itm.purchase_unit = u.unit_code(+) " ;
   $sql .= "   and itm.supplier_code = '$supplier_code' "  if ($supplier_code ne "") ;
   $sql .= "   and i.section_code = '$section_code' " if($section_code ne "") ;
   $sql .= " order by i.description,itm.alter_procedure " ;
   #print "$sql <BR>\n" ;
 
  
   $cur = $dbh->prepare($sql) || &err("BUYING PRICE err $DBI::err .... $DBI::errstr") ;
   $cur->execute() || &err("BUYING PRICE err $DBI::err .... $DBI::errstr") ;
 
 #
 # CSV
 #
  print "\"ITEM_NO\"," ;
  print "\"ITEM\"," ;
  print "\"DESCRIPTION\"," ;
  print "\"DRAWING No.\"," ;
  print "\"REVISION\"," ;
  print "\"STOCK SUBJECT\"," ;
  print "\"CATALOG No.\"," ;
  print "\"STANDARD PRICE\"," ;
  print "\"SUPPLIER PRICE\"," ;
  print "\"ALTER PROCEDURE\"," ;
  print "\"PURCHASE LEAD TIME\"," ;
  print "\"SUPPLIER CODE\"," ;
  print "\"SUPPLIER\"," ;
  print "\"ESTIMATE PRICE\"," ;
  print "\"TRIAL SAMPLE PRICE\"," ;
  print "\"ADJUSTMENT PRICE\"," ;
  print "\"MINIMUN ORDER LOT\"," ;
  print "\"SPLIT ORDER LOT\"," ;
  print "\"MANUFACTUREING COST\"," ;
  print "\"MATERIAL COST\"," ;
  print "\"SEMIFINISH CODE\"," ;
  print "\"CURRENCY\"," ;
  print "\"CREATE_DATE\"," ;
  print "\"OPERATION_DATE\"," ;
  print "\"SECTION\"," ;
  #FLのみ、PO_STK_RATE(在庫変換率)を入力  2003/12/4  By R.Tsutsui
  if ($CONF{'PO_STK_RATE'} eq "1") {
      print "\"P/O SHEET RATE\"," ;
      print "\"P/O SHEET UNIT\"," ;
  }
  print "\n" ; 

  while(@datas = $cur->fetchrow){
      for($j=0;$j<@datas;$j++){
        ${$cur->{NAME}->[$j]} = $datas[$j] ;  
      }
      print "$ITEM_NO," ;
      print "\"$ITEM\"," ;
      print "\"$DESCRIPTION\"," ;
      print "\"$DRAWING_NO\"," ;
      print "\"$DRAWING_REV\"," ;
      print "\"$STOCK_SUBJECT\"," ;
      print "\"$CATALOG_NO\"," ;
      print "$STANDARD_PRICE," ;
      print "$SUPPLIERS_PRICE," ;
      print "$ALTER_PROCEDURE," ;
      print "$PURCHASE_LEADTIME," ;
      print "$SUPPLIER_CODE," ;
      print "\"$SUPPLIER\"," ;
      print "$ESTIMATE_PRICE," ;
      print "$TRIAL_SAMPLE_PRICE," ;
      print "$ADJUSTMENT_PRICE," ;
      print "$MINIMUM_ORDER_LOT," ;
      print "\"$SPLIT_ORDER_LOT\"," ;
      print "$MANUFACTURING_COST," ;
      print "$MATERIAL_COST," ;
      print "$SEMIFINISH_COST," ;
      print "\"$CURR_MARK\"," ;
      print "\"$CREATE_DATE\"," ;
      print "\"$OPERATION_DATE\"," ;
      print "\"$SECTION\"," ;
  #FLのみ、PO_STK_RATE(在庫変換率)を入力  2003/12/4  By R.Tsutsui
      if ($CONF{'PO_STK_RATE'} eq "1") {
          print "$UNIT_PURCHASE_RATE," ;
          print "\"$PURCHASE_UNIT\"," ;
      }
      print "\n" ;
  }
  $cur->finish();

}
